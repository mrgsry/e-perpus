<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request as InternalRequest;
use Illuminate\Support\Facades\Log;

class NineRouterService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;
    protected $model;
    protected $timeout;
    protected $maxTokens;
    protected $temperature;
    protected $appUrl;
    protected $apiClient;

    public function __construct()
    {
        $this->baseUrl = config('services.ninerouter.base_url');
        $this->apiKey = config('services.ninerouter.api_key');
        $this->model = config('services.ninerouter.model');
        $this->timeout = config('services.ninerouter.timeout');
        $this->maxTokens = config('services.ninerouter.max_tokens');
        $this->temperature = config('services.ninerouter.temperature');
        $this->appUrl = config('services.ninerouter.app_url');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);

        // Client for calling local API endpoints
        $this->apiClient = new Client([
            'base_uri' => $this->appUrl,
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Send a chat message to 9router AI
     *
     * @param string $userMessage
     * @param array $conversationHistory
     * @return string|null
     */
    public function chat(string $userMessage, array $conversationHistory = []): ?string
    {
        // Check if this is a direct database query that can be answered immediately
        $databaseAnswer = $this->answerFromDatabaseIfPossible($userMessage);

        if ($databaseAnswer !== null) {
            Log::info('Direct database answer provided', ['question' => $userMessage]);
            return $databaseAnswer;
        }

        try {
            $messages = $this->buildMessages($userMessage, $conversationHistory);
            $functions = $this->getFunctionDefinitions();

            $payload = [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => $this->temperature,
                'max_tokens' => $this->maxTokens,
                'functions' => $functions,
                'function_call' => 'auto',
                'stream' => false,
            ];

            Log::info('9router AI Request', ['model' => $this->model]);

            $response = $this->client->post('/v1/chat/completions', [
                'json' => $payload,
            ]);

            $responseBody = $response->getBody()->getContents();
            $body = $this->parseResponseBody($responseBody);

            // Check if AI wants to call a function
            if (isset($body['choices'][0]['message']['function_call'])) {
                return $this->handleFunctionCall($body['choices'][0]['message'], $messages);
            }

            // Return the AI's text response
            return $body['choices'][0]['message']['content'] ?? null;
        } catch (GuzzleException $e) {
            Log::error('9router AI Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            // Try to extract keyword and search database as fallback
            $keyword = $this->extractBookKeyword($userMessage);
            if ($keyword !== '') {
                $searchResult = $this->searchBooks($keyword, 5);
                if (($searchResult['found'] ?? false) === true) {
                    Log::info('Fallback to database search successful', ['keyword' => $keyword]);
                    return $this->formatBookList($searchResult, "Saya menemukan beberapa buku terkait \"{$keyword}\":");
                }
            }

            // Enhanced fallback for general questions
            $generalFallback = $this->handleGeneralQuestionFallback($userMessage);
            if ($generalFallback !== null) {
                Log::info('Fallback to general question handling successful', ['question' => $userMessage]);
                return $generalFallback;
            }

            return "Maaf, layanan AI sedang lambat/tidak tersedia. Berikut beberapa pertanyaan yang dapat saya jawab:\n• \"buku apa saja yang tersedia\"\n• \"statistik total buku\"\n• \"cari buku [kata kunci]\"\n• \"stok buku [nama buku]\"\n• \"buku populer\"\n• \"buku kategori [jenis]\"\n• Informasi tentang registrasi, login, peminjaman, pengembalian, atau denda";
        } catch (\Exception $e) {
            Log::error('9router AI Unexpected Error', [
                'message' => $e->getMessage(),
            ]);
            return 'Maaf, terjadi kendala pada layanan AI. Silakan coba lagi dalam beberapa saat.';
        }
    }

    /**
     * Build messages array for AI
     *
     * @param string $userMessage
     * @param array $conversationHistory
     * @return array
     */
    protected function buildMessages(string $userMessage, array $conversationHistory = []): array
    {
        $systemPrompt = $this->getSystemPrompt();

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Add conversation history if provided
        foreach ($conversationHistory as $msg) {
            $messages[] = $msg;
        }

        // Add current user message
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        return $messages;
    }

    /**
     * Get system prompt for AI
     *
     * @return string
     */
    protected function getSystemPrompt(): string
    {
        $bookContext = $this->getBookIndexContext();

        $prompt = "Anda adalah asisten virtual cerdas untuk Perpustakaan Digital SIPUSAKA.

TUGAS UTAMA:
- Menjawab pertanyaan tentang seluruh aspek perpustakaan: katalog buku, ketersediaan stok, prosedur registrasi mahasiswa, cara login, aturan peminjaman, durasi peminjaman, pengembalian, dan denda.
- Menjadi representasi resmi dari sistem SIPUSAKA.

INFORMASI PENTING TENTANG WEBSITE:
- Registrasi: Mahasiswa dapat melakukan registrasi di halaman pendaftaran dengan memasukkan NIM yang valid. Akun akan melalui proses validasi.
- Login: Mahasiswa login menggunakan NIM dan password yang telah didaftarkan.
- Peminjaman: Mahasiswa dapat meminjam buku melalui katalog jika stok tersedia. Cek status peminjaman di menu 'Cek Status Peminjaman'.
- Pengembalian: Buku harus dikembalikan tepat waktu. Keterlambatan akan dikenakan denda.
- Denda: Denda dihitung berdasarkan durasi keterlambatan.

ATURAN KERJA (WAJIB DIIKUTI):
1. JIKA USER BERTANYA TENTANG BUKU/STOK: Gunakan DATA BUKU di bawah ini sebagai referensi utama.
2. JIKA USER BERTANYA TENTANG PROSEDUR: Jelaskan dengan jelas dan langkah-langkah yang mudah dipahami.
3. BERSIKAP PROFESIONAL: Gunakan bahasa Indonesia yang ramah, sopan, dan membantu.
4. JANGAN PERNAH:
   - Memberikan informasi palsu atau spekulatif di luar konteks perpustakaan.
   - Mengatakan Anda tidak memiliki akses ke informasi website.
5. FORMAT JAWABAN:
   - Ringkas, informatif, dan profesional.
   - Gunakan bullet points untuk daftar atau langkah-langkah agar mudah dibaca.";

        if ($bookContext !== '') {
            $prompt .= "\n\n" . $bookContext;
        }

        return $prompt;
    }

    /**
     * Load and format book index JSON as context for AI.
     * This provides AI with pre-loaded knowledge of all books without needing function calls.
     *
     * @return string
     */
    protected function getBookIndexContext(): string
    {
        try {
            $indexPath = storage_path('app/book-index.json');

            if (!file_exists($indexPath)) {
                Log::warning('Book index file not found. Run: php artisan books:generate-index');
                return '';
            }

            $indexData = json_decode(file_get_contents($indexPath), true);

            if (!is_array($indexData) || empty($indexData['books'])) {
                return '';
            }

            $lines = [
                "=== DATA BUKU PERPUSTAKAAN SIPUSAKA ===",
                "Total buku: {$indexData['total_books']} judul | Data per: {$indexData['generated_at']}",
                "",
                "DAFTAR SEMUA BUKU:",
            ];

            foreach ($indexData['books'] as $book) {
                $status = $book['status_ketersediaan'];
                $stokInfo = "stok tersedia: {$book['stok_tersedia']}/{$book['stok_total']}";
                $popularitas = $book['popularitas'] ?? '';

                $lines[] = "- [{$status}] {$book['nama_buku']} | Kategori: {$book['jenis_buku']} | Genre: {$book['genre_buku']} | Penerbit: {$book['penerbit']} | {$stokInfo} | Dipinjam: {$book['borrow_count']}x | {$popularitas}";
            }

            $lines[] = "=== AKHIR DATA BUKU ===";

            Log::info('Book index loaded into AI context', ['total_books' => $indexData['total_books']]);

            return implode("\n", $lines);
        } catch (\Exception $e) {
            Log::error('Failed to load book index', ['message' => $e->getMessage()]);
            return '';
        }
    }

    /**
     * Answer common library data questions directly from database.
     * This keeps stock/book questions reliable even when the upstream AI
     * provider does not execute function calling.
     *
     * @param string $userMessage
     * @return string|null
     */
    protected function answerFromDatabaseIfPossible(string $userMessage): ?string
    {
        $message = trim($userMessage);
        $lowerMessage = mb_strtolower($message);

        // Handle registration questions
        if (preg_match('/\b(registrasi|daftar|mendaftar|pendaftaran)\b/u', $lowerMessage)) {
            return "Anda dapat melakukan registrasi melalui halaman pendaftaran di website SIPUSAKA. Pastikan Anda memasukkan NIM yang valid, dan akun Anda akan melalui proses validasi.";
        }

        // Handle login questions
        if (preg_match('/\b(login|masuk|akses|autentikasi)\b/u', $lowerMessage)) {
            return "Anda dapat login menggunakan NIM dan password yang telah didaftarkan di sistem. Jika lupa password, silakan hubungi admin perpustakaan.";
        }

        // Handle borrowing questions
        if (preg_match('/\b(peminjaman|meminjam|pinjam|borrow)\b/u', $lowerMessage)) {
            return "Anda dapat meminjam buku melalui katalog jika stok tersedia. Cek status peminjaman di menu 'Cek Status Peminjaman' setelah login.";
        }

        // Handle return questions
        if (preg_match('/\b(pengembalian|kembali|return|dikembalikan)\b/u', $lowerMessage)) {
            return "Buku harus dikembalikan tepat waktu. Keterlambatan akan dikenakan denda sesuai dengan kebijakan perpustakaan.";
        }

        // Handle fine/penalty questions
        if (preg_match('/\b(denda|penalty|keterlambatan|late|fee)\b/u', $lowerMessage)) {
            return "Denda dihitung berdasarkan durasi keterlambatan pengembalian buku. Silakan hubungi admin untuk informasi detail denda.";
        }

        // Handle statistics questions
        if (preg_match('/\b(total|jumlah|statistik)\b.*\b(buku|stok|koleksi)\b/u', $lowerMessage)) {
            return $this->formatTotalBooksStats($this->getTotalBooksStats());
        }

        // Handle available books questions
        if (preg_match('/\b(buku tersedia|buku yang tersedia|bisa dipinjam|tersedia apa saja|daftar buku)\b/u', $lowerMessage)) {
            return $this->formatBookList($this->getAvailableBooks(10), 'Berikut beberapa buku yang tersedia untuk dipinjam:');
        }

        // Handle popular books questions
        if (preg_match('/\b(populer|rekomendasi|sering dipinjam|paling banyak dipinjam)\b/u', $lowerMessage)) {
            return $this->formatBookList($this->getPopularBooks(5), 'Berikut rekomendasi buku populer di SIPUSAKA:');
        }

        // Deteksi kategori dari pola "ada buku [kategori]" atau "buku [kategori]"
        if (preg_match('/\b(ada\s+buku|buku)\s+(.+)$/u', $lowerMessage, $matches)) {
            $category = trim($matches[2]);

            if ($category !== '') {
                $result = $this->getBookByCategoryWithFuzzyMatch($category, 10);
                return $this->formatBookList(
                    $result,
                    "Berikut buku dengan kategori/jenis \"{$category}\":"
                );
            }
        }

        if (preg_match('/\b(kategori|jenis)\s+(.+)/u', $lowerMessage, $matches)) {
            $category = trim($matches[2]);

            if ($category !== '') {
                $result = $this->getBookByCategoryWithFuzzyMatch($category, 10);
                return $this->formatBookList(
                    $result,
                    "Berikut buku dengan kategori/jenis \"{$category}\":"
                );
            }
        }

        // Handle stock/availability questions
        if (preg_match('/\b(stok|tersedia|ada|ketersediaan|cari|carikan|apakah|cek|check|lihat)\b/u', $lowerMessage)) {
            $keyword = $this->extractBookKeyword($message);

            if ($keyword !== '') {
                $stockResult = $this->getBookStock($keyword);

                if (($stockResult['found'] ?? false) === true) {
                    return $this->formatBookStock($stockResult);
                }

                $searchResult = $this->searchBooks($keyword, 5);

                if (($searchResult['found'] ?? false) === true) {
                    return $this->formatBookList($searchResult, "Saya menemukan beberapa buku terkait \"{$keyword}\":");
                }

                return "Maaf, buku dengan kata kunci \"{$keyword}\" belum ditemukan di database SIPUSAKA. Silakan coba kata kunci lain atau hubungi admin.";
            }
        }

        return null;
    }

    /**
     * Handle general website-related questions as a fallback
     * when the AI service is unavailable.
     *
     * @param string $userMessage
     * @return string|null
     */
    protected function handleGeneralQuestionFallback(string $userMessage): ?string
    {
        $lowerMessage = mb_strtolower(trim($userMessage));

        // Greeting patterns
        if (preg_match('/\b(halo|hai|hello|hi|selamat|assalamualaikum|p)\b/u', $lowerMessage)) {
            return "Halo! 👋 Selamat datang di SIPUSAKA (Sistem Perpustakaan Digital). Ada yang bisa saya bantu?\n\nSaya bisa membantu Anda dengan:\n• Informasi katalog dan stok buku\n• Cara registrasi dan login\n• Prosedur peminjaman dan pengembalian\n• Informasi denda";
        }

        // How are you
        if (preg_match('/\b(apa kabar|bagaimana kabar|how are you)\b/u', $lowerMessage)) {
            return "Saya baik-baik saja, terima kasih sudah bertanya! 😊 Bagaimana dengan Anda? Ada yang bisa saya bantu seputar perpustakaan SIPUSAKA?";
        }

        // Thanks
        if (preg_match('/\b(terima kasih|thanks|makasih|tengkyu)\b/u', $lowerMessage)) {
            return "Sama-sama! 😊 Senang bisa membantu. Jangan ragu untuk bertanya lagi jika ada hal lain yang ingin Anda ketahui.";
        }

        // Help/What can you do
        if (preg_match('/\b(bantuan|bisa apa|apa saja|fitur|menu|help|tolong)\b/u', $lowerMessage)) {
            return "Saya adalah asisten virtual SIPUSAKA. Berikut yang bisa saya bantu:\n\n📚 **Informasi Buku:**\n• Cari buku berdasarkan judul/kata kunci\n• Cek stok dan ketersediaan buku\n• Lihat buku populer dan rekomendasi\n• Statistik koleksi perpustakaan\n\nℹ️ **Informasi Layanan:**\n• Cara registrasi akun mahasiswa\n• Cara login ke sistem\n• Prosedur peminjaman buku\n• Aturan pengembalian dan denda\n\nSilakan ajukan pertanyaan Anda!";
        }

        // Contact admin
        if (preg_match('/\b(kontak|hubungi|admin|email|telepon|whatsapp|wa)\b/u', $lowerMessage)) {
            return "Untuk menghubungi admin perpustakaan SIPUSAKA, Anda dapat:\n• Mengunjungi loket perpustakaan langsung\n• Menghubungi melalui email atau kontak yang tersedia di halaman utama\n\nSilakan login terlebih dahulu jika Anda membutuhkan bantuan terkait akun atau peminjaman.";
        }

        // Location/Address
        if (preg_match('/\b(lokasi|alamat|dimana|lokasi perpustakaan|address)\b/u', $lowerMessage)) {
            return "Perpustakaan SIPUSAKA dapat diakses secara online melalui website ini. Untuk kunjungan fisik, silakan hubungi admin untuk informasi alamat dan jam operasional.";
        }

        // Operating hours
        if (preg_match('/\b(jam buka|jam operasional|jam layanan|buka|tutup)\b/u', $lowerMessage)) {
            return "Layanan perpustakaan digital SIPUSAKA dapat diakses 24 jam melalui website ini. Untuk layanan offline, silakan hubungi admin untuk informasi jam operasional.";
        }

        return null;
    }

    /**
     * Extract likely book keyword from user message.
     *
     * @param string $message
     * @return string
     */
    protected function extractBookKeyword(string $message): string
    {
        $keyword = preg_replace('/\b(apakah|apa|ada|stok|buku|tersedia|ketersediaan|cari|carikan|tolong|dong|cek|check|lihat|di|perpustakaan|sipusaka|berapa|jumlah|nya|masih|untuk|dipinjam)\b/iu', ' ', $message);
        $keyword = preg_replace('/[?!.:,;]/u', ' ', $keyword);
        $keyword = trim(preg_replace('/\s+/u', ' ', $keyword));

        return $keyword;
    }

    /**
     * Format stock search result.
     *
     * @param array $result
     * @return string
     */
    protected function formatBookStock(array $result): string
    {
        $books = $result['books'] ?? [];

        if (empty($books)) {
            return 'Maaf, buku yang Anda cari belum ditemukan di database SIPUSAKA.';
        }

        $lines = ['Berikut informasi stok buku yang saya temukan:'];

        foreach ($books as $book) {
            $status = ($book['stok_tersedia'] ?? 0) > 0 ? 'tersedia untuk dipinjam' : 'sedang habis';
            $lines[] = "- {$book['nama_buku']} ({$book['jenis_buku']}): stok tersedia {$book['stok_tersedia']} dari {$book['stok_total']} total, status {$status}.";
        }

        return implode("\n", $lines);
    }

    /**
     * Format book list result.
     *
     * @param array $result
     * @param string $title
     * @return string
     */
    protected function formatBookList(array $result, string $title): string
    {
        $books = $result['books'] ?? [];

        if (empty($books)) {
            return 'Maaf, belum ada data buku yang sesuai dengan pertanyaan Anda.';
        }

        $lines = [$title];

        foreach ($books as $book) {
            $stok = $book['stok_tersedia'] ?? 0;
            $extra = isset($book['borrow_count']) ? ", dipinjam {$book['borrow_count']} kali" : '';
            $lines[] = "- {$book['nama_buku']} ({$book['jenis_buku']}), stok tersedia: {$stok}{$extra}.";
        }

        return implode("\n", $lines);
    }

    /**
     * Format total books stats.
     *
     * @param array $stats
     * @return string
     */
    protected function formatTotalBooksStats(array $stats): string
    {
        return "Statistik koleksi SIPUSAKA saat ini:\n"
            . "- Total judul buku: {$stats['total_buku']}\n"
            . "- Total stok buku: {$stats['total_stok']}\n"
            . "- Stok tersedia: {$stats['stok_tersedia']}\n"
            . "- Stok sedang dipinjam: {$stats['stok_dipinjam']}\n"
            . "- Judul yang masih tersedia: {$stats['buku_tersedia']}\n"
            . "- Judul yang stoknya habis: {$stats['buku_habis']}";
    }

    /**
     * Get function definitions for AI
     *
     * @return array
     */
    protected function getFunctionDefinitions(): array
    {
        $baseUrl = $this->appUrl;

        return [
            [
                'name' => 'get_book_stock',
                'description' => 'Mendapatkan informasi stok buku berdasarkan nama buku. Gunakan ini ketika user bertanya tentang ketersediaan atau stok buku tertentu. Endpoint: POST ' . $baseUrl . '/api/books/stock',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'book_name' => [
                            'type' => 'string',
                            'description' => 'Nama buku yang ingin dicari (bisa sebagian nama)',
                        ],
                    ],
                    'required' => ['book_name'],
                ],
            ],
            [
                'name' => 'search_books',
                'description' => 'Mencari buku berdasarkan keyword. Gunakan ini ketika user ingin mencari buku dengan kata kunci tertentu. Endpoint: POST ' . $baseUrl . '/api/books/search',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'keyword' => [
                            'type' => 'string',
                            'description' => 'Kata kunci untuk mencari buku',
                        ],
                        'limit' => [
                            'type' => 'integer',
                            'description' => 'Jumlah maksimal hasil (default 5)',
                        ],
                    ],
                    'required' => ['keyword'],
                ],
            ],
            [
                'name' => 'get_available_books',
                'description' => 'Mendapatkan daftar buku yang tersedia (stok > 0). Gunakan ini ketika user bertanya buku apa saja yang bisa dipinjam. Endpoint: POST ' . $baseUrl . '/api/books/available',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'limit' => [
                            'type' => 'integer',
                            'description' => 'Jumlah maksimal hasil (default 10)',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'get_book_by_category',
                'description' => 'Mendapatkan buku berdasarkan kategori/jenis. Gunakan ini ketika user bertanya tentang buku dalam kategori tertentu. Endpoint: POST ' . $baseUrl . '/api/books/category',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'category' => [
                            'type' => 'string',
                            'description' => 'Kategori/jenis buku',
                        ],
                        'limit' => [
                            'type' => 'integer',
                            'description' => 'Jumlah maksimal hasil (default 10)',
                        ],
                    ],
                    'required' => ['category'],
                ],
            ],
            [
                'name' => 'get_popular_books',
                'description' => 'Mendapatkan daftar buku populer (paling banyak dipinjam). Gunakan ini ketika user bertanya tentang buku populer atau rekomendasi. Endpoint: POST ' . $baseUrl . '/api/books/popular',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'limit' => [
                            'type' => 'integer',
                            'description' => 'Jumlah maksimal hasil (default 5)',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'get_total_books_stats',
                'description' => 'Mendapatkan statistik total buku (total buku, buku tersedia, buku dipinjam). Gunakan ini ketika user bertanya tentang jumlah atau statistik buku. Endpoint: GET ' . $baseUrl . '/api/books/stats',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [],
                ],
            ],
        ];
    }

    /**
     * Handle function call from AI
     *
     * @param array $message
     * @param array $messages
     * @return string|null
     */
    protected function handleFunctionCall(array $message, array $messages): ?string
    {
        $functionName = $message['function_call']['name'];
        $arguments = json_decode($message['function_call']['arguments'], true);

        Log::info('AI Function Call', [
            'function' => $functionName,
            'arguments' => $arguments,
        ]);

        // Execute the function
        $functionResult = $this->executeFunction($functionName, $arguments);

        // Send the function result back to AI
        $messages[] = $message;
        $messages[] = [
            'role' => 'function',
            'name' => $functionName,
            'content' => json_encode($functionResult),
        ];

        try {
            $response = $this->client->post('/v1/chat/completions', [
                'json' => [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => $this->temperature,
                    'max_tokens' => $this->maxTokens,
                    'stream' => false,
                ],
            ]);

            $body = $this->parseResponseBody($response->getBody()->getContents());
            return $body['choices'][0]['message']['content'] ?? null;
        } catch (GuzzleException $e) {
            Log::error('9router AI Function Response Error', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Parse JSON or Server-Sent Events response from 9router.
     *
     * @param string $responseBody
     * @return array
     */
    protected function parseResponseBody(string $responseBody): array
    {
        $decoded = json_decode($responseBody, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        $content = '';
        $functionCall = null;
        $lastChunk = null;

        foreach (preg_split('/\r\n|\r|\n/', trim($responseBody)) as $line) {
            $line = trim($line);

            if ($line === '' || !str_starts_with($line, 'data:')) {
                continue;
            }

            $data = trim(substr($line, 5));

            if ($data === '[DONE]') {
                break;
            }

            $chunk = json_decode($data, true);

            if (!is_array($chunk)) {
                continue;
            }

            $lastChunk = $chunk;
            $delta = $chunk['choices'][0]['delta'] ?? [];

            if (isset($delta['content'])) {
                $content .= $delta['content'];
            }

            if (isset($delta['function_call'])) {
                $functionCall['name'] = $functionCall['name'] ?? '';
                $functionCall['arguments'] = $functionCall['arguments'] ?? '';

                if (isset($delta['function_call']['name'])) {
                    $functionCall['name'] .= $delta['function_call']['name'];
                }

                if (isset($delta['function_call']['arguments'])) {
                    $functionCall['arguments'] .= $delta['function_call']['arguments'];
                }
            }
        }

        if ($functionCall) {
            return [
                'choices' => [
                    [
                        'message' => [
                            'role' => 'assistant',
                            'content' => null,
                            'function_call' => $functionCall,
                        ],
                    ],
                ],
            ];
        }

        return [
            'id' => $lastChunk['id'] ?? null,
            'object' => 'chat.completion',
            'choices' => [
                [
                    'message' => [
                        'role' => 'assistant',
                        'content' => $content !== '' ? $content : null,
                    ],
                ],
            ],
        ];
    }

    /**
     * Execute function based on name by calling the registered internal API endpoint.
     *
     * @param string $functionName
     * @param array $arguments
     * @return array
     */
    protected function executeFunction(string $functionName, array $arguments): array
    {
        $result = [];
        switch ($functionName) {
            case 'get_book_stock':
                $result = $this->callApiEndpoint('POST', '/api/books/stock', [
                    'book_name' => $arguments['book_name'] ?? '',
                ]);
                break;

            case 'search_books':
                $result = $this->callApiEndpoint('POST', '/api/books/search', [
                    'keyword' => $arguments['keyword'] ?? '',
                    'limit' => $arguments['limit'] ?? 5,
                ]);
                break;

            case 'get_available_books':
                $result = $this->callApiEndpoint('POST', '/api/books/available', [
                    'limit' => $arguments['limit'] ?? 10,
                ]);
                break;

            case 'get_book_by_category':
                $result = $this->callApiEndpoint('POST', '/api/books/category', [
                    'category' => $arguments['category'] ?? '',
                    'limit' => $arguments['limit'] ?? 10,
                ]);
                break;

            case 'get_popular_books':
                $result = $this->callApiEndpoint('POST', '/api/books/popular', [
                    'limit' => $arguments['limit'] ?? 5,
                ]);
                break;

            case 'get_total_books_stats':
                $result = $this->callApiEndpoint('GET', '/api/books/stats');
                break;

            default:
                return ['error' => 'Function not found'];
        }

        // Format book data for AI if it's a book-related function
        if (isset($result['books']) && is_array($result['books'])) {
            $result['books'] = array_map([$this, 'formatBookDataForAI'], $result['books']);
        }
        // For stats, ensure it's directly returned
        if ($functionName === 'get_total_books_stats' && isset($result['stats'])) {
            return $result['stats'];
        }

        return $result;
    }

    /**
     * Format book data to be more readable for AI.
     * Removes unnecessary fields and provides clear descriptions.
     *
     * @param array $book
     * @return array
     */
    protected function formatBookDataForAI(array $book): array
    {
        return [
            'nama_buku' => $book['nama_buku'] ?? 'Tidak Diketahui',
            'jenis_buku' => $book['jenis_buku'] ?? 'Tidak Diketahui',
            'stok_tersedia' => $book['stok_tersedia'] ?? 0,
            'stok_total' => $book['stok_total'] ?? 0,
            'status_ketersediaan' => ($book['stok_tersedia'] ?? 0) > 0 ? 'Tersedia' : 'Habis',
            'deskripsi_tambahan' => 'Buku ini termasuk dalam kategori ' . ($book['jenis_buku'] ?? 'Tidak Diketahui') . '.',
        ];
    }

    /**
     * Call local Laravel API endpoint internally.
     * This keeps data access endpoint-based without depending on Apache path configuration.
     *
     * @param string $method
     * @param string $uri
     * @param array $payload
     * @return array
     */
    protected function callApiEndpoint(string $method, string $uri, array $payload = []): array
    {
        try {
            $request = InternalRequest::create($uri, $method, $payload, [], [], [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]);

            $response = app()->handle($request);
            $content = $response->getContent();
            $decoded = json_decode($content, true);

            if (!is_array($decoded)) {
                return [
                    'error' => 'Invalid API response',
                    'status' => $response->getStatusCode(),
                    'raw' => $content,
                ];
            }

            if ($response->getStatusCode() >= 400) {
                return [
                    'error' => 'API endpoint error',
                    'status' => $response->getStatusCode(),
                    'response' => $decoded,
                ];
            }

            return $decoded;
        } catch (\Exception $e) {
            Log::error('Local API Endpoint Error', [
                'method' => $method,
                'uri' => $uri,
                'payload' => $payload,
                'message' => $e->getMessage(),
            ]);

            return [
                'error' => 'Gagal mengambil data dari endpoint lokal',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get book stock by name
     *
     * @param string $bookName
     * @return array
     */
    protected function getBookStock(string $bookName): array
    {
        return $this->callApiEndpoint('POST', '/api/books/stock', [
            'book_name' => $bookName,
        ]);
    }

    /**
     * Search books by keyword
     *
     * @param string $keyword
     * @param int $limit
     * @return array
     */
    protected function searchBooks(string $keyword, int $limit = 5): array
    {
        return $this->callApiEndpoint('POST', '/api/books/search', [
            'keyword' => $keyword,
            'limit' => $limit,
        ]);
    }

    /**
     * Get available books
     *
     * @param int $limit
     * @return array
     */
    protected function getAvailableBooks(int $limit = 10): array
    {
        return $this->callApiEndpoint('POST', '/api/books/available', [
            'limit' => $limit,
        ]);
    }

    /**
     * Get books by category
     *
     * @param string $category
     * @param int $limit
     * @return array
     */
    protected function getBookByCategory(string $category, int $limit = 10): array
    {
        return $this->callApiEndpoint('POST', '/api/books/category', [
            'category' => $category,
            'limit' => $limit,
        ]);
    }

    /**
     * Get books by category with fuzzy matching
     * Finds categories that are similar to the user input
     *
     * @param string $userCategory
     * @param int $limit
     * @return array
     */
    protected function getBookByCategoryWithFuzzyMatch(string $userCategory, int $limit = 10): array
    {
        // First try exact match
        $result = $this->getBookByCategory($userCategory, $limit);

        if (($result['found'] ?? false) === true && !empty($result['books'])) {
            return $result;
        }

        // If no exact match, get all unique categories and find the best match
        $allBooks = $this->callApiEndpoint('POST', '/api/books/available', ['limit' => 1000]);

        if (!($allBooks['found'] ?? false) || empty($allBooks['books'])) {
            return $result; // Return empty result if no books available
        }

        // Extract unique categories
        $categories = [];
        foreach ($allBooks['books'] as $book) {
            $jenis = $book['jenis_buku'] ?? '';
            if ($jenis && !in_array($jenis, $categories)) {
                $categories[] = $jenis;
            }
        }

        if (empty($categories)) {
            return $result;
        }

        // Find the best matching category using similarity
        $bestMatch = null;
        $bestSimilarity = 0;

        foreach ($categories as $cat) {
            $similarity = 0;
            similar_text(mb_strtolower($userCategory), mb_strtolower($cat), $similarity);

            if ($similarity > $bestSimilarity) {
                $bestSimilarity = $similarity;
                $bestMatch = $cat;
            }
        }

        // If similarity is above 50%, use the best match
        if ($bestMatch && $bestSimilarity >= 50) {
            return $this->getBookByCategory($bestMatch, $limit);
        }

        return $result;
    }

    /**
     * Get popular books
     *
     * @param int $limit
     * @return array
     */
    protected function getPopularBooks(int $limit = 5): array
    {
        return $this->callApiEndpoint('POST', '/api/books/popular', [
            'limit' => $limit,
        ]);
    }

    /**
     * Get total books statistics
     *
     * @return array
     */
    protected function getTotalBooksStats(): array
    {
        return $this->callApiEndpoint('GET', '/api/books/stats');
    }
}