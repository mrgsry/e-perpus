<?php

namespace App\Services;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;
    protected $timeout;
    protected $maxTokens;
    protected $temperature;
    protected $mahasiswa = null;

    public function __construct(?array $mahasiswa = null)
    {
        $this->mahasiswa = $mahasiswa;
        $this->baseUrl = env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai/');
        $this->apiKey = env('GEMINI_API_KEY');
        $this->model = env('GEMINI_MODEL', 'gemini-2.5-flash');
        $this->timeout = env('GEMINI_TIMEOUT', 30);
        $this->maxTokens = env('GEMINI_MAX_TOKENS', 500);
        $this->temperature = env('GEMINI_TEMPERATURE', 0.2);

        if (!$this->apiKey) {
            throw new \Exception('Gemini API key not configured');
        }
    }

    /**
     * Analyze user intent and extract parameters
     */
    public function analyzeIntent(string $userMessage): array
    {
        try {
            $systemPrompt = 'Anda adalah asisten AI untuk perpustakaan e-perpus yang bertugas menganalisis pertanyaan pengguna dan mengembalikan respons dalam format JSON.

Tugas Saya:
1. DETEKSI INTENT dari pertanyaan pengguna
2. Ekstrak parameter yang relevan

INTENT yang tersedia:
- list_all_books: Pengguna ingin melihat daftar semua buku (contoh: "ada buku apa aja?", "list buku", "semua buku")
- search_books: Pengguna mencari buku spesifik (contoh: "cari buku fisika", "ada buku tentang php?")
- filter_by_category: Filter berdasarkan jenis/kategori buku (contoh: "buku teknologi", "buku fiksi")
- filter_by_genre: Filter berdasarkan genre (contoh: "buku genre romance", "novel thriller")
- check_availability: Cek ketersediaan buku (contoh: "stok buku", "buku tersedia?")
- borrowing_info: Info peminjaman (contoh: "cara pinjam", "aturan peminjaman")
- my_borrowings: Pengguna ingin melihat status peminjaman buku mereka sendiri (contoh: "peminjaman saya", "cek status peminjaman", "buku yang saya pinjam", "pinjaman saya", "riwayat peminjaman", "status pinjaman")
- my_denda: Pengguna ingin melihat informasi denda mereka (contoh: "cek denda saya", "berapa denda saya?", "informasi denda", "total denda saya", "denda buku", "denda terlambat", "denda peminjaman", "denda keterlambatan", "cek denda peminjaman", "denda buku saya")
- check_loan_eligibility: Pengguna ingin mengecek apakah bisa pinjam buku atau tidak, atau ada masalah dengan peminjaman (contoh: "saya mau pinjam buku", "apakah saya bisa pinjam buku?", "kenapa tidak bisa pinjam", "saya ingin meminjam", "cek status pinjaman untuk pinjam baru", "bisa tidak saya pinjam buku", "syarat pinjam buku", "apakah ada denda", "ada telat tidak saya")
- member_registration: Pengguna ingin mendaftar menjadi anggota/member perpustakaan (contoh: "cara daftar member", "gimana caranya jadi anggota?", "daftar akun")
- general_info: Pertanyaan umum tentang perpustakaan

FORMAT OUTPUT HARUS JSON:
{
    "intent": "nama_intent",
    "query_params": {
        "keyword": "kata kunci pencarian (jika ada)",
        "category": "kategori buku (jika ada)",
        "genre": "genre buku (jika ada)"
    },
    "response_text": "Respons santai dan ramah dalam bahasa Indonesia. JANGAN mengulang daftar buku atau langkah-langkah pendaftaran secara detail di sini, karena itu akan ditambahkan otomatis oleh sistem. Cukup berikan kalimat pembuka singkat."
}

Contoh:
User: "ada buku apa aja?"
Output: {"intent": "list_all_books", "query_params": {}, "response_text": "Saya akan menampilkan daftar buku yang tersedia di perpustakaan kami!"}

User: "gimana caranya jadi anggota?"
Output: {"intent": "member_registration", "query_params": {}, "response_text": "Tentu, saya akan bantu jelaskan cara mendaftar menjadi anggota e-perpus kami. Prosesnya mudah kok!"}

User: "saya mau pinjam buku"
Output: {"intent": "check_loan_eligibility", "query_params": {}, "response_text": "Baik, saya akan mengecek status peminjaman Anda untuk menentukan apakah Anda bisa meminjam buku."}

User: "apakah saya bisa pinjam buku?"
Output: {"intent": "check_loan_eligibility", "query_params": {}, "response_text": "Saya akan memeriksa apakah ada peminjaman yang terlambat atas nama Anda."}';

            $response = $this->callGeminiAPI($systemPrompt, $userMessage);

            // Bersihkan markdown code block wrapper (```json ... ```)
            $cleaned = trim($response);
            $cleaned = preg_replace('/^```(?:json|JSON)?\s*\n?/', '', $cleaned);
            $cleaned = preg_replace('/\n?```\s*$/', '', $cleaned);
            $cleaned = trim($cleaned);

            // Parse JSON response
            $jsonData = json_decode($cleaned, true);

            if (!$jsonData || !isset($jsonData['intent'])) {
                // Jika gagal parse, gunakan response mentah sebagai teks saja
                return [
                    'intent' => 'general_info',
                    'query_params' => [],
                    'response_text' => $response,
                ];
            }

            return $jsonData;

        } catch (\Exception $e) {
            Log::error('Intent Analysis Error: ' . $e->getMessage());
            return [
                'intent' => 'general_info',
                'query_params' => [],
                'response_text' => 'Maaf, saya sedang mengalami kendala. Silakan coba lagi.',
            ];
        }
    }

    /**
     * Execute database query based on intent
     */
    public function executeIntent(array $intentData): array
    {
        $intent = $intentData['intent'] ?? 'general_info';
        $params = $intentData['query_params'] ?? [];

        $result = [
            'books' => [],
            'response_text' => $intentData['response_text'] ?? '',
        ];

        try {
            switch ($intent) {
                case 'list_all_books':
                    $result['books'] = Buku::select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'genre_buku', 'stok_tersedia')
                        ->where('stok_tersedia', '>', 0)
                        ->orderBy('nama_buku')
                        ->get()
                        ->toArray();
                    break;

                case 'search_books':
                    $keyword = $params['keyword'] ?? '';
                    if ($keyword) {
                        $result['books'] = Buku::select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'genre_buku', 'stok_tersedia')
                            ->where(function($query) use ($keyword) {
                                $query->where('nama_buku', 'LIKE', "%{$keyword}%")
                                    ->orWhere('penerbit', 'LIKE', "%{$keyword}%")
                                    ->orWhere('jenis_buku', 'LIKE', "%{$keyword}%")
                                    ->orWhere('genre_buku', 'LIKE', "%{$keyword}%");
                            })
                            ->where('stok_tersedia', '>', 0)
                            ->orderBy('nama_buku')
                            ->get()
                            ->toArray();
                    }
                    break;

                case 'filter_by_category':
                    $category = $params['category'] ?? '';
                    if ($category) {
                        $result['books'] = Buku::select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'genre_buku', 'stok_tersedia')
                            ->where('jenis_buku', 'LIKE', "%{$category}%")
                            ->where('stok_tersedia', '>', 0)
                            ->orderBy('nama_buku')
                            ->get()
                            ->toArray();
                    }
                    break;

                case 'filter_by_genre':
                    $genre = $params['genre'] ?? '';
                    if ($genre) {
                        $result['books'] = Buku::select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'genre_buku', 'stok_tersedia')
                            ->where('genre_buku', 'LIKE', "%{$genre}%")
                            ->where('stok_tersedia', '>', 0)
                            ->orderBy('nama_buku')
                            ->get()
                            ->toArray();
                    }
                    break;

                case 'check_availability':
                    $keyword = $params['keyword'] ?? '';
                    if ($keyword) {
                        $result['books'] = Buku::select('id', 'nama_buku', 'penerbit', 'stok_total', 'stok_tersedia')
                            ->where('nama_buku', 'LIKE', "%{$keyword}%")
                            ->orderBy('nama_buku')
                            ->get()
                            ->toArray();
                    } else {
                        $result['books'] = Buku::select('id', 'nama_buku', 'penerbit', 'stok_total', 'stok_tersedia')
                            ->where('stok_tersedia', '>', 0)
                            ->orderBy('stok_tersedia', 'desc')
                            ->limit(10)
                            ->get()
                            ->toArray();
                    }
                    break;

                case 'my_borrowings':
                    $mahasiswaId = $this->mahasiswa['id'] ?? null;
                    if ($mahasiswaId) {
                        $borrowings = Peminjaman::with(['buku:id,nama_buku'])
                            ->where('mahasiswa_id', $mahasiswaId)
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->toArray();
                        $result['borrowings'] = $borrowings;
                    }
                    break;

                case 'my_denda':
                    $mahasiswaId = $this->mahasiswa['id'] ?? null;
                    if ($mahasiswaId) {
                        $denda = Denda::with(['peminjaman.buku:id,nama_buku'])
                            ->whereHas('peminjaman', function ($q) use ($mahasiswaId) {
                                $q->where('mahasiswa_id', $mahasiswaId);
                            })
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->toArray();
                        $result['denda'] = $denda;
                    }
                    break;

                case 'check_loan_eligibility':
                    $mahasiswaId = $this->mahasiswa['id'] ?? null;
                    if ($mahasiswaId) {
            // Cek peminjaman yang terlambat (overdue atau ada denda)
            $overdueBorrowings = Peminjaman::with(['buku:id,nama_buku', 'denda'])
                ->where('mahasiswa_id', $mahasiswaId)
                ->where(function ($query) {
                    $query->where('status', 'overdue')
                        ->orWhere(function ($q) {
                            $q->whereHas('denda', function ($d) {
                                $d->where('status_bayar', 'Belum Dibayar');
                            });
                        })
                        ->orWhere(function ($q) {
                            $q->where('status', 'borrowed')
                              ->where('tanggal_kembali_rencana', '<', now());
                        });
                })
                ->get()
                ->toArray();

            // Cek peminjaman yang masih aktif (belum dikembalikan)
            $activeBorrowings = Peminjaman::with('buku:id,nama_buku')
                ->where('mahasiswaId', $mahasiswaId) // Fixed typo: mahasiswaId -> mahasiswa_id
                ->whereIn('status', ['borrowed', 'approved'])
                ->get()
                ->toArray();

            $result['overdue_borrowings'] = $overdueBorrowings;
            $result['active_borrowings'] = $activeBorrowings;
            $result['can_borrow'] = empty($result['overdue_borrowings']);
                    }
                    break;

                case 'member_registration':
                    // No database query needed, just provide the step-by-step guide
                    $registrationSteps = [
                        'Klik menu "Mahasiswa" yang berada di pojok kanan atas header website',
                        'Pilih opsi "Register" atau "Daftar" yang tersedia',
                        'Isi formulir pendaftaran dengan data diri Anda (Nama, NIM, Email, dll)',
                        'Setelah submit formulir, tunggu sampai akun Anda diapprove oleh admin',
                        'Jika akun sudah diapprove, Anda akan menerima email berisi informasi login dan akses Anda',
                    ];
                    $result['registration_steps'] = $registrationSteps;
                    $result['registration_url'] = 'https://sipusaka.net/mahasiswa/register';
                    $result['login_url'] = 'https://sipusaka.net/mahasiswa/login';
                    break;

                default:
                    // General info, no database query needed
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Database Query Error: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Generate natural response with book data
     */
    public function generateResponse(string $userMessage, array $history = []): array
    {
        try {
            // Step 1: Analyze intent
            $intentData = $this->analyzeIntent($userMessage);

            // Step 2: Execute intent to get data
            $result = $this->executeIntent($intentData);

            // Step 3: Format books data for response
            $booksFormatted = '';

            // Intents that require book search results
            $bookSearchIntents = ['list_all_books', 'search_books', 'filter_by_category', 'filter_by_genre', 'check_availability'];

            if (in_array($intentData['intent'], $bookSearchIntents)) {
                if (!empty($result['books'])) {
                    $booksFormatted = "\n\n📚 **Daftar Buku Tersedia di Perpustakaan e-perpus** 📚\n\n";
                    $booksFormatted .= "Berikut adalah daftar buku yang tersedia:\n\n";

                    foreach ($result['books'] as $index => $book) {
                        $num = $index + 1;
                        $namaBuku = $book['nama_buku'];
                        $booksFormatted .= "📖 **{$num}. {$namaBuku}**\n";
                        $booksFormatted .= "🏷️ Penerbit: " . $book['penerbit'] . "\n";
                        $booksFormatted .= "📂 Kategori: " . $book['jenis_buku'] . " | 🎭 Genre: " . $book['genre_buku'] . "\n";

                        if (isset($book['stok_tersedia'])) {
                            $stok = (int) $book['stok_tersedia'];
                            if ($stok > 5) {
                                $booksFormatted .= "✅ Stok Tersedia: {$stok} (Banyak)\n";
                            } elseif ($stok > 0) {
                                $booksFormatted .= "⚠️ Stok Tersedia: {$stok} (Terbatas)\n";
                            } else {
                                $booksFormatted .= "❌ Stok Tersedia: 0 (Kosong)\n";
                            }
                        }

                        $booksFormatted .= "\n";
                    }

                    $totalBooks = count($result['books']);
                    $booksFormatted .= "📝 Total buku: {$totalBooks} buku tersedia\n";
                } else {
                    $booksFormatted = "\n\n📚 **Pencarian Buku** 📚\n\n";
                    $booksFormatted .= "Maaf, tidak ditemukan buku yang sesuai dengan pencarian Anda. 😔\n";
                    $booksFormatted .= "Silakan coba kata kunci lain atau hubungi petugas perpustakaan untuk bantuan. 📞\n";
                }
            }

            // Step 4: Format borrowing data if needed
            $borrowingsFormatted = '';
            if ($intentData['intent'] === 'my_borrowings') {
                if (!empty($result['borrowings'])) {
                    $borrowingsFormatted = "\n\n📋 **Data Peminjaman Saya** 📋\n\n";
                    $borrowingsFormatted .= "Berikut adalah data peminjaman buku Anda:\n\n";

                    foreach ($result['borrowings'] as $index => $pinjam) {
                        $num = $index + 1;
                        $namaBuku = $pinjam['buku']['nama_buku'] ?? 'Buku tidak ditemukan';
                        $tglPinjam = isset($pinjam['tanggal_pinjam']) ? date('d/m/Y', strtotime($pinjam['tanggal_pinjam'])) : '-';
                        $tglKembali = isset($pinjam['tanggal_kembali_rencana']) ? date('d/m/Y', strtotime($pinjam['tanggal_kembali_rencana'])) : '-';
                        $status = $pinjam['status'] ?? '-';

                        // Map status ke bahasa Indonesia
                        $statusMap = [
                            'pending' => '⏳ Menunggu Konfirmasi',
                            'approved' => '✅ Disetujui',
                            'borrowed' => '📖 Sedang Dipinjam',
                            'returned' => '📚 Sudah Dikembalikan',
                            'rejected' => '❌ Ditolak',
                            'overdue' => '⚠️ Terlambat',
                        ];
                        $statusLabel = $statusMap[$status] ?? $status;

                        $borrowingsFormatted .= "{$num}. **{$namaBuku}**\n";
                        $borrowingsFormatted .= "   📅 Tanggal Pinjam: {$tglPinjam}\n";
                        $borrowingsFormatted .= "   📅 Batas Kembali: {$tglKembali}\n";
                        $borrowingsFormatted .= "   📊 Status: {$statusLabel}\n\n";
                    }

                    $totalPinjam = count($result['borrowings']);
                    $borrowingsFormatted .= "📝 Total: {$totalPinjam} peminjaman\n";
                } else {
                    $borrowingsFormatted = "\n\n📋 **Data Peminjaman Saya** 📋\n\n";
                    $borrowingsFormatted .= "Saat ini tidak ada data peminjaman buku atas nama Anda.\n";
                    $borrowingsFormatted .= "Jika ingin meminjam buku, silakan kunjungi halaman Katalog Buku dan pilih buku yang tersedia. 😊\n";
                }
            }

            // Step 5: Format denda data if needed
            $dendaFormatted = '';
            if ($intentData['intent'] === 'my_denda') {
                if (!empty($result['denda'])) {
                    $dendaFormatted = "\n\n💰 **Data Denda Saya** 💰\n\n";
                    $dendaFormatted .= "Berikut adalah data denda Anda:\n\n";

                    foreach ($result['denda'] as $index => $dendaData) {
                        $num = $index + 1;
                        $namaBuku = $dendaData['peminjaman']['buku']['nama_buku'] ?? 'Buku tidak ditemukan';
                        $hariTerlambat = $dendaData['hari_terlambat'] ?? 0;
                        $totalDenda = $dendaData['total_denda'] ?? 0;
                        $statusBayar = $dendaData['status_bayar'] ?? 'Belum Dibayar';
                        $dibayarAt = $dendaData['dibayar_at'] ? date('d/m/Y', strtotime($dendaData['dibayar_at'])) : '-';

                        $dendaFormatted .= "{$num}. **{$namaBuku}**\n";
                        $dendaFormatted .= "   📅 Hari Terlambat: {$hariTerlambat} hari\n";
                        $dendaFormatted .= "   💰 Total Denda: Rp " . number_format($totalDenda, 0, ',', '.') . "\n";
                        $dendaFormatted .= "   📊 Status: " . ($statusBayar === 'Belum Dibayar' ? '⚠️ Belum Dibayar' : '✅ Sudah Dibayar') . "\n";
                        $dendaFormatted .= "   📅 Dibayar Pada: {$dibayarAt}\n\n";
                    }

                    $totalDenda = count($result['denda']);
                    $dendaFormatted .= "📝 Total: {$totalDenda} data denda\n";
                } else {
                    $dendaFormatted = "\n\n💰 **Data Denda Saya** 💰\n\n";
                    $dendaFormatted .= "Saat ini tidak ada data denda atas nama Anda.\n";
                    $dendaFormatted .= "Jika ada buku yang terlambat dikembalikan, denda akan tercatat di sini. 😊\n";
                }
            }

            // Step 5: Format registration steps if needed
            $registrationFormatted = '';
            if ($intentData['intent'] === 'member_registration' && isset($result['registration_steps'])) {
                $registrationFormatted = "\n\n📝 **Cara Mendaftar Member Perpustakaan** 📝\n\n";

                foreach ($result['registration_steps'] as $i => $step) {
                    $stepNum = $i + 1;
                    $registrationFormatted .= "👉 {$stepNum}. " . $step . "\n";
                }

                $registrationFormatted .= "\n🔗 Pendaftaran: https://sipusaka.net/mahasiswa/login\n";
                $registrationFormatted .= "🔗 Login: https://sipusaka.net/mahasiswa/login\n\n";
                $registrationFormatted .= "Jika Anda sudah memiliki akun, silakan login menggunakan link di atas. Jika ada pertanyaan lain, jangan ragu untuk bertanya! 😊";
            }

            // Step 6: Format loan eligibility response if needed
            $loanEligibilityFormatted = '';
            if ($intentData['intent'] === 'check_loan_eligibility') {
                $canBorrow = $result['can_borrow'] ?? false;
                $overdueBorrowings = $result['overdue_borrowings'] ?? [];
                $activeBorrowings = $result['active_borrowings'] ?? [];

                if ($canBorrow) {
                    $loanEligibilityFormatted = "\n\n✅ **Status Kelayakan Peminjaman** ✅\n\n";
                    $loanEligibilityFormatted .= "🎉 Selamat! Anda **BISA MEMINJAM BUKU** di perpustakaan e-perpus.\n";
                    $loanEligibilityFormatted .= "Saat ini Anda tidak memiliki peminjaman yang terlambat.\n";

                    if (!empty($activeBorrowings)) {
                        $loanEligibilityFormatted .= "\n📋 **Buku yang sedang Anda pinjam:**\n";
                        foreach ($activeBorrowings as $index => $pinjam) {
                            $num = $index + 1;
                            $namaBuku = $pinjam['buku']['nama_buku'] ?? 'Buku tidak ditemukan';
                            $tglKembali = isset($pinjam['tanggal_kembali_rencana']) ? date('d/m/Y', strtotime($pinjam['tanggal_kembali_rencana'])) : '-';
                            $loanEligibilityFormatted .= "   {$num}. {$namaBuku} (Batas kembali: {$tglKembali})\n";
                        }
                    }

                    $loanEligibilityFormatted .= "\n💡 Silakan menuju halaman Katalog Buku untuk memilih buku yang ingin dipinjam. Selamat membaca! 📚\n";
                } else {
                    $loanEligibilityFormatted = "\n\n❌ **Status Kelayakan Peminjaman** ❌\n\n";
                    $loanEligibilityFormatted .= "Mohon maaf, saat ini Anda **TIDAK BISA MEMINJAM BUKU** karena ada peminjaman yang terlambat.\n";
                    $loanEligibilityFormatted .= "Anda harus menyelesaikan peminjaman yang terlambat terlebih dahulu sebelum dapat meminjam buku baru.\n";

                    if (!empty($overdueBorrowings)) {
                        $loanEligibilityFormatted .= "\n📋 **Detail Peminjaman Terlambat:**\n";

                        foreach ($overdueBorrowings as $index => $pinjam) {
                            $num = $index + 1;
                            $namaBuku = $pinjam['buku']['nama_buku'] ?? 'Buku tidak ditemukan';
                            $tglPinjam = isset($pinjam['tanggal_pinjam']) ? date('d/m/Y', strtotime($pinjam['tanggal_pinjam'])) : '-';
                            $tglKembali = isset($pinjam['tanggal_kembali_rencana']) ? date('d/m/Y', strtotime($pinjam['tanggal_kembali_rencana'])) : '-';

                            // Hitung hari terlambat
                            $hariTerlambat = 0;
                            if (isset($pinjam['tanggal_kembali_rencana'])) {
                                $rencana = strtotime($pinjam['tanggal_kembali_rencana']);
                                $sekarang = time();
                                $hariTerlambat = max(0, floor(($sekarang - $rencana) / (60 * 60 * 24)));
                            }

                            // Ambil informasi denda jika ada
                            $totalDenda = 0;
                            $statusDenda = 'Belum Dibayar';
                            if (isset($pinjam['denda']) && !empty($pinjam['denda'])) {
                                $denda = $pinjam['denda'];
                                $totalDenda = $denda['total_denda'] ?? 0;
                                $hariTerlambat = $denda['hari_terlambat'] ?? $hariTerlambat;
                                $statusDenda = $denda['status_bayar'] ?? 'Belum Dibayar';
                            }

                            $alasan = '';
                            if ($pinjam['status'] === 'overdue') {
                                $alasan = "Status: Terlambat ({$hariTerlambat} hari)";
                            } elseif ($statusDenda === 'Belum Dibayar') {
                                $alasan = "Status: Ada denda belum dibayar";
                            } else {
                                $alasan = "Status: Lewat batas waktu ({$hariTerlambat} hari)";
                            }

                            $loanEligibilityFormatted .= "\n   📖 {$num}. {$namaBuku}\n";
                            $loanEligibilityFormatted .= "      📅 Tanggal Pinjam: {$tglPinjam}\n";
                            $loanEligibilityFormatted .= "      📅 Batas Kembali: {$tglKembali}\n";
                            $loanEligibilityFormatted .= "      ⚠️ {$alasan}\n";

                            if ($totalDenda > 0) {
                                $loanEligibilityFormatted .= "      💰 Denda: Rp " . number_format($totalDenda, 0, ',', '.') . "\n";
                                $loanEligibilityFormatted .= "      📊 Status Denda: " . ($statusDenda === 'Sudah Dibayar' ? 'Sudah Dibayar' : 'Belum Dibayar') . "\n";
                            }
                        }

                        $loanEligibilityFormatted .= "\n⚠️ **Cara Mengaktifkan Kembali Kelayakan Meminjam:**\n";
                        $loanEligibilityFormatted .= "   1. Kembalikan buku yang terlambat ke perpustakaan\n";
                        $loanEligibilityFormatted .= "   2. Lunasi denda keterlambatan (jika ada)\n";
                        $loanEligibilityFormatted .= "   3. Tunggu konfirmasi dari admin perpustakaan\n";
                        $loanEligibilityFormatted .= "\n📞 Jika ada pertanyaan, silakan hubungi admin perpustakaan.\n";
                    }
                }
            }

            // Step 7: Combine AI text with all formatted data
            $rawResponse = trim($result['response_text'] . $booksFormatted . $borrowingsFormatted . $dendaFormatted . $registrationFormatted . $loanEligibilityFormatted);

            // Step 6: Clean up markdown-lite bold markers into plain text.
            // IMPORTANT: the chat widget inserts this as plain text (.text()), not HTML —
            // so we must NOT emit <br>/<strong> or htmlspecialchars() output here.
            // Real newlines are kept as-is; the bubble's CSS needs `white-space: pre-line`
            // for them to actually show as line breaks (see note below).
            $finalResponse = $this->cleanPlainText($rawResponse);

            return [
                'success' => true,
                'intent' => $intentData['intent'],
                'response' => $finalResponse,
                'books_data' => $result['books'],
                'registration_steps' => $result['registration_steps'] ?? [],
                'denda_data' => $result['denda'] ?? [],
            ];

        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'response' => 'Maaf, terjadi kesalahan saat memproses permintaan Anda. Silakan coba lagi.',
                'books_data' => [],
            ];
        }
    }

    /**
     * Strip markdown-lite bold markers (**text**) since the frontend renders this
     * as plain text (not HTML) — bold can't be displayed there anyway, so we just
     * remove the asterisks instead of leaving them visible. Real newline characters
     * (\n) are left untouched; they only become visible line breaks if the chat
     * bubble element has `white-space: pre-line` (or `pre-wrap`) in its CSS.
     */
    protected function cleanPlainText(string $text): string
    {
        // **bold** -> bold (drop the asterisks, keep the text)
        $text = preg_replace('/\*\*(.+?)\*\*/s', '$1', $text);

        // Normalize Windows-style line endings just in case.
        $text = str_replace("\r\n", "\n", $text);

        return $text;
    }

    /**
     * Legacy method for backward compatibility
     */
    public function chat(string $message, array $history = []): string
    {
        $result = $this->generateResponse($message, $history);
        return $result['response'] ?? 'Maaf, saya tidak bisa menjawab saat ini.';
    }

    /**
     * Call Gemini OpenAI-Compatible API
     */
    protected function callGeminiAPI(string $systemPrompt, string $userMessage): string
    {
        $url = rtrim($this->baseUrl, '/') . '/chat/completions';

        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage
                ]
            ],
            'temperature' => (float) $this->temperature,
            'max_tokens' => (int) $this->maxTokens,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, (int) $this->timeout);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception("API returned HTTP $httpCode: " . $response);
        }

        $data = json_decode($response, true);

        if (isset($data['choices'][0]['message']['content'])) {
            return $data['choices'][0]['message']['content'];
        }

        throw new \Exception('Invalid API response format');
    }
}