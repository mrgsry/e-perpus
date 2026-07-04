<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Mahasiswa;
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

            // Try to get AI response using Gemini Service
            $aiService = new GeminiService();
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
            'daftar member' => 'Untuk mendaftar sebagai anggota, silakan ikuti langkah-langkah berikut: 1) Klik menu Mahasiswa di header, 2) Pilih Register, 3) Isi data Anda, 4) Tunggu approval admin, 5) Cek email untuk informasi login.',
            'cara daftar' => 'Untuk mendaftar sebagai anggota, silakan ikuti langkah-langkah berikut: 1) Klik menu Mahasiswa di header, 2) Pilih Register, 3) Isi data Anda, 4) Tunggu approval admin, 5) Cek email untuk informasi login.',
            'register' => 'Untuk mendaftar sebagai anggota, silakan ikuti langkah-langkah berikut: 1) Klik menu Mahasiswa di header, 2) Pilih Register, 3) Isi data Anda, 4) Tunggu approval admin, 5) Cek email untuk informasi login.',
            'daftar akun' => 'Untuk mendaftar akun, silakan ikuti langkah-langkah berikut: 1) Klik menu Mahasiswa di header, 2) Pilih Register, 3) Isi data Anda, 4) Tunggu approval admin, 5) Cek email untuk informasi login.',
            'gimana daftar' => 'Untuk mendaftar sebagai anggota, silakan ikuti langkah-langkah berikut: 1) Klik menu Mahasiswa di header, 2) Pilih Register, 3) Isi data Anda, 4) Tunggu approval admin, 5) Cek email untuk informasi login.',
            'gimana jadi member' => 'Untuk menjadi member, silakan ikuti langkah-langkah berikut: 1) Klik menu Mahasiswa di header, 2) Pilih Register, 3) Isi data Anda, 4) Tunggu approval admin, 5) Cek email untuk informasi login.',
        ];

        foreach ($keywords as $key => $response) {
            if (str_contains($message, $key)) {
                return $response;
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

        foreach ($unrelatedTopics as $word) {
            if (str_contains($message, $word)) {
                return 'Maaf, saya tidak bisa membahas topik tersebut. Saya adalah asisten perpustakaan. Ada yang bisa saya bantu terkait layanan perpustakaan?';
            }
        }

        return 'Maaf, saya tidak mengerti pertanyaan Anda. Bisa dijelaskan lebih detail?';
    }
}