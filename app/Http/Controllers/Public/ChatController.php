<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function verifyNim(Request $request)
    {
        $request->validate([
            'nim' => 'required|string',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIM tidak terdaftar. Silakan daftarkan NIM anda pada bagian register.',
            ], 404);
        }

        // Only allow approved mahasiswa to use chat
        if ($mahasiswa->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Status akun Anda tidak valid. Silakan hubungi admin.',
                'status' => $mahasiswa->status,
            ], 403);
        }

        // Store mahasiswa info in session
        session([
            'chat_mahasiswa_id' => $mahasiswa->id,
            'chat_mahasiswa_nim' => $mahasiswa->nim,
            'chat_mahasiswa_nama' => $mahasiswa->nama,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil!',
            'mahasiswa' => [
                'id' => $mahasiswa->id,
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
            ],
        ]);
    }

    public function sendMessage(Request $request)
    {
        // Check if mahasiswa is verified
        if (!session('chat_mahasiswa_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan verifikasi NIM terlebih dahulu.',
            ], 401);
        }

        $sessionId = $request->input('session_id');
        $message = $request->input('message');

        if (!$sessionId) {
            $sessionId = Str::uuid();
            ChatSession::create([
                'session_id' => $sessionId,
                'mahasiswa_id' => session('chat_mahasiswa_id'),
                'mahasiswa_nim' => session('chat_mahasiswa_nim'),
                'mahasiswa_nama' => session('chat_mahasiswa_nama'),
            ]);
        }

        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();

        $userMessageCount = ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'user')
            ->count();

        ChatMessage::create([
            'session_id' => $sessionId,
            'sender_type' => 'user',
            'message' => $message,
        ]);

        // Auto-connect to admin if 3 or more user messages
        if ($userMessageCount + 1 >= 6) {
            $session->is_connected_to_admin = true;
            $session->save();
        }

        $botResponse = null;
        if (!$session->is_connected_to_admin) {
            // Get conversation history for context (last 5 messages)
            $history = ChatMessage::where('session_id', $sessionId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->reverse()
                ->map(function ($msg) {
                    return [
                        'role' => $msg->sender_type === 'bot' ? 'assistant' : ($msg->sender_type === 'user' ? 'user' : 'assistant'),
                        'content' => $msg->message,
                    ];
                })->toArray();

            // Try to get AI response using Gemini Service (with mahasiswa context)
            $aiService = new GeminiService([
                'id' => session('chat_mahasiswa_id'),
                'nim' => session('chat_mahasiswa_nim'),
                'nama' => session('chat_mahasiswa_nama'),
            ]);
            $aiResult = $aiService->generateResponse($message, $history);
            $botResponse = $aiResult['response'] ?? null;

            // Fallback to keyword if AI fails
            if (!$botResponse) {
                Log::warning('AI failed to respond, falling back to keywords');
                $botResponse = $this->getIntelligentResponse($message);
            }

            $isDefaultResponse = in_array($botResponse, [
                'Maaf, saya tidak mengerti pertanyaan Anda. Bisa dijelaskan lebih detail?',
                'Saya belum bisa menjawab itu. Apakah ada yang lain bisa saya bantu?',
            ]);

            if ($isDefaultResponse) {
                $session->bot_fail_count += 1;
                $session->save();

                if ($session->bot_fail_count >= 3) {
                    $session->is_connected_to_admin = true;
                    $session->save();
                }
            }
        }

        $responseMessage = null;
        $botMessage = null;
        if (!$session->is_connected_to_admin && $botResponse) {
            $botMessage = ChatMessage::create([
                'session_id' => $sessionId,
                'sender_type' => 'bot',
                'message' => $botResponse,
            ]);
            $responseMessage = $botResponse;
        }

        $status = $session->is_connected_to_admin ? 'connected_admin' : 'bot';

        return response()->json([
            'status' => $status,
            'session_id' => $sessionId,
            'message' => $responseMessage,
            'message_id' => $botMessage ? $botMessage->id : null,
            'is_connected_to_admin' => $session->is_connected_to_admin,
            'bot_fail_count' => $session->bot_fail_count,
        ]);
    }

    public function connectToAdmin(Request $request)
    {
        $sessionId = $request->input('session_id');

        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'message' => 'Session ID tidak ditemukan',
            ], 400);
        }

        $session = ChatSession::where('session_id', $sessionId)->first();

        if ($session) {
            $session->is_connected_to_admin = true;
            $session->save();

            // Add system message indicating manual connection
            ChatMessage::create([
                'session_id' => $sessionId,
                'sender_type' => 'bot',
                'message' => 'Mohon tunggu sebentar, Anda sedang dialihkan ke Admin kami...',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil terhubung ke admin',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sesi chat tidak ditemukan',
        ], 404);
    }

    public function getMessages(Request $request)
    {
        $sessionId = $request->input('session_id');
        $lastMessageId = $request->input('last_message_id', 0);

        if (!$sessionId) {
            return response()->json([
                'messages' => [],
                'session_closed' => false,
            ]);
        }

        $session = ChatSession::where('session_id', $sessionId)->first();

        if (!$session) {
            return response()->json([
                'messages' => [],
                'session_closed' => true,
            ]);
        }

        $messages = ChatMessage::where('session_id', $sessionId)
            ->where('id', '>', $lastMessageId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
            'session_closed' => $session->status === 'closed',
            'status' => $session->is_connected_to_admin ? 'connected_admin' : 'bot',
            'is_connected_to_admin' => $session->is_connected_to_admin,
            'bot_fail_count' => $session->bot_fail_count,
        ]);
    }

    private function getIntelligentResponse(string $message): string
    {
        $message = strtolower(trim($message));

        // Library-related keywords
        $keywords = [
            'halo' => 'Halo! Ada yang bisa saya bantu hari ini?',
            'hii' => 'Halo! Selamat datang! Ada yang bisa saya bantu?',
            'hi' => 'Halo! Selamat datang di sistem kami!',
            'terima kasih' => 'Sama-sama! Senang bisa membantu. Jika ada pertanyaan lain, silakan tanyakan.',
            'sama-sama' => 'Senang bisa membantu! Ada hal lain yang ingin ditanyakan?',
            'jam buka' => 'Kami buka setiap hari Senin-Jumat pukul 08:00-17:00 dan Sabtu 09:00-13:00.',
            'lokasi' => 'Kantor kami berada di Jl. Raya No. 123, Jakarta. Apakah Anda memerlukan informasi lain?',
            'alamat' => 'Alamat kami: Jl. Raya No. 123, Jakarta. Silakan datang pada jam operasional.',
            'pinjam buku' => 'Untuk meminjam buku, silakan isi formulir peminjaman di menu Pinjam Buku. Isi data buku yang ingin dipinjam.',
            'peminjaman' => 'Proses peminjaman buku dapat dilakukan melalui halaman Pinjam Buku. Pastikan NIM Anda sudah terdaftar.',
            'denda' => 'Denda keterlambatan peminjaman buku adalah Rp 1.000 per hari. Silakan cek status peminjaman Anda.',
            'bantuan' => 'Saya siap membantu! Silakan jelaskan apa yang Anda butuhkan.',
            'ketentuan' => 'Syarat dan ketentuan dapat dibaca di halaman Ketentuan Layanan kami.',
            'fitur' => 'Sistem ini menyediakan fitur peminjaman buku, riwayat peminjaman, dan notifikasi jadwal pengembalian.',
            'admin' => 'Jika Anda membutuhkan bantuan lebih lanjut, chat ini dapat dihubungkan ke admin kami.',
            'chat admin' => 'Tentu! Jika bot tidak dapat membantu, percakapan akan otomatis terhubung ke admin.',
            'status' => 'Untuk mengecek status peminjaman, silakan kunjungi halaman Cek Status Peminjaman.',
            'kontak' => 'Anda dapat menghubungi kami di email: support@sipusaka.com atau telepon: (021) 1234567.',
            'email' => 'Email kami adalah support@sipusaka.com. Kami akan merespons secepat mungkin.',
            'telepon' => 'Silakan hubungi kami di (021) 1234567 pada jam operasional.',
            'cara pinjam buku' => 'Cara meminjam buku: 1) Cari buku di katalog, 2) Klik Pinjam, 3) Isi formulir dengan NIM Anda, 4) Tunggu konfirmasi admin.',
            'total buku' => 'Anda dapat melihat daftar buku yang tersedia di halaman Katalog Buku.',
            'kategori' => 'Kategori buku yang tersedia meliputi fiksi, non-fiksi, pelajaran, dan referensi akademik.',
            'daftar member' => 'Tentu! Berikut adalah langkah-langkah lengkap untuk mendaftar sebagai anggota perpustakaan digital kami:\n\n1. **Klik menu Mahasiswa** pada header di bagian pojok kanan atas halaman\n2. **Pilih ke bagian Register** (halaman pendaftaran)\n3. **Masukan data Anda** dengan lengkap dan akurat\n4. **Jika sudah, tunggu sampai akun Anda di approve** oleh admin perpustakaan\n5. **Jika akun sudah di approve, akan ada email masuk** berisi informasi akses Anda\n\nSetelah menerima email, Anda bisa login menggunakan NIM dan password yang diberikan. Jika ada pertanyaan lain, jangan ragu untuk bertanya!',
            'cara daftar' => 'Tentu! Berikut adalah langkah-langkah lengkap untuk mendaftar sebagai anggota perpustakaan digital kami:\n\n1. **Klik menu Mahasiswa** pada header di bagian pojok kanan atas halaman\n2. **Pilih ke bagian Register** (halaman pendaftaran)\n3. **Masukan data Anda** dengan lengkap dan akurat\n4. **Jika sudah, tunggu sampai akun Anda di approve** oleh admin perpustakaan\n5. **Jika akun sudah di approve, akan ada email masuk** berisi informasi akses Anda\n\nSetelah menerima email, Anda bisa login menggunakan NIM dan password yang diberikan. Jika ada pertanyaan lain, jangan ragu untuk bertanya!',
            'register' => 'Tentu! Berikut adalah langkah-langkah lengkap untuk mendaftar sebagai anggota perpustakaan digital kami:\n\n1. **Klik menu Mahasiswa** pada header di bagian pojok kanan atas halaman\n2. **Pilih ke bagian Register** (halaman pendaftaran)\n3. **Masukan data Anda** dengan lengkap dan akurat\n4. **Jika sudah, tunggu sampai akun Anda di approve** oleh admin perpustakaan\n5. **Jika akun sudah di approve, akan ada email masuk** berisi informasi akses Anda\n\nSetelah menerima email, Anda bisa login menggunakan NIM dan password yang diberikan. Jika ada pertanyaan lain, jangan ragu untuk bertanya!',
            'daftar akun' => 'Tentu! Berikut adalah langkah-langkah lengkap untuk mendaftar sebagai anggota perpustakaan digital kami:\n\n1. **Klik menu Mahasiswa** pada header di bagian pojok kanan atas halaman\n2. **Pilih ke bagian Register** (halaman pendaftaran)\n3. **Masukan data Anda** dengan lengkap dan akurat\n4. **Jika sudah, tunggu sampai akun Anda di approve** oleh admin perpustakaan\n5. **Jika akun sudah di approve, akan ada email masuk** berisi informasi akses Anda\n\nSetelah menerima email, Anda bisa login menggunakan NIM dan password yang diberikan. Jika ada pertanyaan lain, jangan ragu untuk bertanya!',
            'gimana daftar' => 'Tentu! Berikut adalah langkah-langkah lengkap untuk mendaftar sebagai anggota perpustakaan digital kami:\n\n1. **Klik menu Mahasiswa** pada header di bagian pojok kanan atas halaman\n2. **Pilih ke bagian Register** (halaman pendaftaran)\n3. **Masukan data Anda** dengan lengkap dan akurat\n4. **Jika sudah, tunggu sampai akun Anda di approve** oleh admin perpustakaan\n5. **Jika akun sudah di approve, akan ada email masuk** berisi informasi akses Anda\n\nSetelah menerima email, Anda bisa login menggunakan NIM dan password yang diberikan. Jika ada pertanyaan lain, jangan ragu untuk bertanya!',
            'gimana jadi member' => 'Tentu! Berikut adalah langkah-langkah lengkap untuk mendaftar sebagai anggota perpustakaan digital kami:\n\n1. **Klik menu Mahasiswa** pada header di bagian pojok kanan atas halaman\n2. **Pilih ke bagian Register** (halaman pendaftaran)\n3. **Masukan data Anda** dengan lengkap dan akurat\n4. **Jika sudah, tunggu sampai akun Anda di approve** oleh admin perpustakaan\n5. **Jika akun sudah di approve, akan ada email masuk** berisi informasi akses Anda\n\nSetelah menerima email, Anda bisa login menggunakan NIM dan password yang diberikan. Jika ada pertanyaan lain, jangan ragu untuk bertanya!',
        ];

        foreach ($keywords as $key => $response) {
            if (str_contains($message, $key)) {
                return $response;
            }
        }

        // Borrowing data query keywords (dynamic database fallback when AI fails)
        $borrowingKeywords = [
            'peminjaman saya',
            'pinjaman saya',
            'status pinjaman',
            'status peminjaman',
            'cek pinjaman',
            'cek peminjaman',
            'buku yang saya pinjam',
            'riwayat peminjaman',
            'history pinjaman',
            'data peminjaman',
            'lihat peminjaman',
        ];
        foreach ($borrowingKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return $this->getMahasiswaBorrowingsResponse();
            }
        }

                // Filter unrelated topics
                $unrelatedTopics = [
                    'basket',
                    'bola',
                    'sepak',
                    'game',
                    'main',
                    'film',
                    'nonton',
                    'lagu',
                    'musik',
                    'makanan',
                    'resep',
                    'masak',
                    'travel',
                    'wisata',
                    'liburan',
                    'joke',
                    'lucu',
                    'gombal',
                    'curhat',
                    'pacar',
                    'jodoh',
                    'politik',
                    'berita',
                    'prediksi',
                    'togel',
                    'jud',
                    'saham',
                    'crypto',
                    'bitcoin',
                    'ramal',
                ];

                // Denda-related keywords
                $dendaKeywords = [
                    'denda',
                    'cek denda',
                    'denda saya',
                    'informasi denda',
                    'total denda',
                    'denda buku',
                    'denda terlambat',
                    'denda peminjaman',
                    'berapa denda',
                    'denda keterlambatan',
                ];
                foreach ($dendaKeywords as $keyword) {
                    if (str_contains($message, $keyword)) {
                        return $this->getMahasiswaDendaResponse();
                    }
                }

                // Loan eligibility keywords (fallback when AI fails)
                $loanEligibilityKeywords = [
                    'saya mau pinjam',
                    'mau pinjam buku',
                    'bisa pinjam',
                    'ingin pinjam',
                    'pinjam buku baru',
                    'saya mau meminjam',
                    'gimana cara pinjam',
                    'bisa tidak saya',
                    'syarat pinjam',
                    'cek pinjam',
                    'apakah bisa',
                    'kapan bisa pinjam',
                ];
                foreach ($loanEligibilityKeywords as $keyword) {
                    if (str_contains($message, $keyword)) {
                        return $this->getMahasiswaLoanEligibilityResponse();
                    }
                }

        foreach ($unrelatedTopics as $word) {
            if (str_contains($message, $word)) {
                return 'Maaf, saya tidak bisa membahas topik tersebut. Saya adalah asisten perpustakaan. Ada yang bisa saya bantu terkait layanan perpustakaan?';
            }
        }

        return 'Maaf, saya tidak mengerti pertanyaan Anda. Bisa dijelaskan lebih detail?';
    }

    /**
     * Query borrowing data for the authenticated mahasiswa
     */
    private function getMahasiswaBorrowingsResponse(): string
    {
        $mahasiswaId = session('chat_mahasiswa_id');
        if (!$mahasiswaId) {
            return 'Maaf, Anda belum terverifikasi. Silakan masukkan NIM terlebih dahulu.';
        }

        $borrowings = Peminjaman::with('buku')
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($borrowings->isEmpty()) {
            return 'Saat ini tidak ada data peminjaman buku atas nama Anda. Jika ingin meminjam buku, silakan kunjungi halaman Katalog Buku.';
        }

        $response = "Berikut adalah data peminjaman buku Anda:\n\n";

        foreach ($borrowings as $index => $pinjam) {
            $num = $index + 1;
            $namaBuku = $pinjam->buku->nama_buku ?? 'Buku tidak ditemukan';
            $tglPinjam = $pinjam->tanggal_pinjam ? date('d/m/Y', strtotime($pinjam->tanggal_pinjam)) : '-';
            $tglKembali = $pinjam->tanggal_kembali_rencana ? date('d/m/Y', strtotime($pinjam->tanggal_kembali_rencana)) : '-';

            $statusMap = [
                'pending' => 'Menunggu Konfirmasi',
                'approved' => 'Disetujui',
                'borrowed' => 'Sedang Dipinjam',
                'returned' => 'Sudah Dikembalikan',
                'rejected' => 'Ditolak',
                'overdue' => 'Terlambat',
            ];
            $statusLabel = $statusMap[$pinjam->status] ?? $pinjam->status;

            $response .= "{$num}. {$namaBuku}\n";
            $response .= "   Tanggal Pinjam: {$tglPinjam}\n";
            $response .= "   Batas Kembali: {$tglKembali}\n";
            $response .= "   Status: {$statusLabel}\n\n";
        }

        $response .= "Total: {$borrowings->count()} peminjaman.";

        return $response;
    }

    /**
     * Check loan eligibility for the authenticated mahasiswa
     * Fallback method when AI fails to respond
     */
    private function getMahasiswaLoanEligibilityResponse(): string
    {
        $mahasiswaId = session('chat_mahasiswa_id');
        if (!$mahasiswaId) {
            return 'Maaf, Anda belum terverifikasi. Silakan masukkan NIM terlebih dahulu.';
        }

        $now = now();

        // Cek peminjaman yang overdue
        $overdueBorrowings = Peminjaman::with('buku')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where(function ($query) use ($now) {
                $query->where('status', 'overdue')
                    ->orWhere(function ($q) use ($now) {
                        $q->where('status', 'borrowed')
                          ->where('tanggal_kembali_rencana', '<', $now);
                    });
            })
            ->orderBy('tanggal_kembali_rencana', 'asc')
            ->get();

        if ($overdueBorrowings->isEmpty()) {
            // Tidak ada peminjaman terlambat -> BISA PINJAM
            $response = "✅ **Status Kelayakan Peminjaman** ✅\n\n";
            $response .= "Selamat! Anda **BISA MEMINJAM BUKU** di perpustakaan e-perpus.\n";
            $response .= "Saat ini Anda tidak memiliki peminjaman yang terlambat.\n\n";

            // Tampilkan buku yang sedang dipinjam (jika ada)
            $activeBorrowings = Peminjaman::with('buku')
                ->where('mahasiswa_id', $mahasiswaId)
                ->whereIn('status', ['borrowed', 'approved'])
                ->get();

            if ($activeBorrowings->isNotEmpty()) {
                $response .= "Buku yang sedang Anda pinjam:\n";
                foreach ($activeBorrowings as $index => $pinjam) {
                    $num = $index + 1;
                    $namaBuku = $pinjam->buku->nama_buku ?? 'Buku tidak ditemukan';
                    $tglKembali = $pinjam->tanggal_kembali_rencana ? date('d/m/Y', strtotime($pinjam->tanggal_kembali_rencana)) : '-';
                    $response .= "{$num}. {$namaBuku} (Batas kembali: {$tglKembali})\n";
                }
            }

            $response .= "\nSilakan menuju halaman Katalog Buku untuk memilih buku yang ingin dipinjam. Selamat membaca!";
        } else {
            // Ada peminjaman terlambat -> TIDAK BISA PINJAM
            $response = "❌ **Status Kelayakan Peminjaman** ❌\n\n";
            $response .= "Mohon maaf, saat ini Anda **TIDAK BISA MEMINJAM BUKU** karena ada peminjaman yang terlambat.\n";
            $response .= "Anda harus menyelesaikan peminjaman yang terlambat terlebih dahulu.\n\n";
            $response .= "Detail Peminjaman Terlambat:\n\n";

            foreach ($overdueBorrowings as $index => $pinjam) {
                $num = $index + 1;
                $namaBuku = $pinjam->buku->nama_buku ?? 'Buku tidak ditemukan';
                $tglPinjam = $pinjam->tanggal_pinjam ? date('d/m/Y', strtotime($pinjam->tanggal_pinjam)) : '-';
                $tglKembali = $pinjam->tanggal_kembali_rencana ? date('d/m/Y', strtotime($pinjam->tanggal_kembali_rencana)) : '-';

                // Hitung hari terlambat
                $hariTerlambat = 0;
                if ($pinjam->tanggal_kembali_rencana) {
                    $rencana = $pinjam->tanggal_kembali_rencana->timestamp;
                    $sekarang = time();
                    $hariTerlambat = max(0, floor(($sekarang - $rencana) / (60 * 60 * 24)));
                }

                // Ambil data denda jika ada relasi denda
                $totalDenda = 0;
                if ($pinjam->relationLoaded('denda') && $pinjam->denda) {
                    $totalDenda = $pinjam->denda->total_denda;
                    $hariTerlambat = $pinjam->denda->hari_terlambat ?? $hariTerlambat;
                }

                $alasan = $pinjam->status === 'overdue'
                    ? "Terlambat {$hariTerlambat} hari"
                    : "Lewat batas waktu {$hariTerlambat} hari";

                $response .= "{$num}. {$namaBuku}\n";
                $response .= "   Tanggal Pinjam: {$tglPinjam}\n";
                $response .= "   Batas Kembali: {$tglKembali}\n";
                $response .= "   Alasan: {$alasan}\n";

                if ($totalDenda > 0) {
                    $response .= "   Denda: Rp " . number_format($totalDenda, 0, ',', '.') . "\n";
                }

                $response .= "\n";
            }

            $response .= "Cara Mengaktifkan Kembali Kelayakan Meminjam:\n";
            $response .= "1. Kembalikan buku yang terlambat ke perpustakaan\n";
            $response .= "2. Lunasi denda keterlambatan (jika ada)\n";
            $response .= "3. Tunggu konfirmasi dari admin perpustakaan\n\n";
            $response .= "Jika ada pertanyaan, silakan hubungi admin perpustakaan.";
        }

        return $response;
    }
}