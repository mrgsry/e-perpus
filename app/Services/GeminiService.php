<?php

namespace App\Services;

use App\Models\Buku;
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

    public function __construct()
    {
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

Tugas Anda:
1. DETEKSI INTENT dari pertanyaan pengguna
2. Ekstrak parameter yang relevan

INTENT yang tersedia:
- list_all_books: Pengguna ingin melihat daftar semua buku (contoh: "ada buku apa aja?", "list buku", "semua buku")
- search_books: Pengguna mencari buku spesifik (contoh: "cari buku fisika", "ada buku tentang php?")
- filter_by_category: Filter berdasarkan jenis/kategori buku (contoh: "buku teknologi", "buku fiksi")
- filter_by_genre: Filter berdasarkan genre (contoh: "buku genre romance", "novel thriller")
- check_availability: Cek ketersediaan buku (contoh: "stok buku", "buku tersedia?")
- borrowing_info: Info peminjaman (contoh: "cara pinjam", "aturan peminjaman")
- general_info: Pertanyaan umum tentang perpustakaan

FORMAT OUTPUT HARUS JSON:
{
    "intent": "nama_intent",
    "query_params": {
        "keyword": "kata kunci pencarian (jika ada)",
        "category": "kategori buku (jika ada)",
        "genre": "genre buku (jika ada)"
    },
    "response_text": "Respons santai dan ramah dalam bahasa Indonesia"
}

Contoh:
User: "ada buku apa aja?"
Output: {"intent": "list_all_books", "query_params": {}, "response_text": "Saya akan menampilkan daftar buku yang tersedia di perpustakaan kami!"}';

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
                        $booksFormatted .= "   🏷️ *Penerbit: " . $book['penerbit'] . "*\n";
                        $booksFormatted .= "   📂 Kategori: " . $book['jenis_buku'] . " | 🎭 Genre: " . $book['genre_buku'] . "\n";
                        
                        if (isset($book['stok_tersedia'])) {
                            $stok = (int)$book['stok_tersedia'];
                            if ($stok > 5) {
                                $booksFormatted .= "   ✅ Stok Tersedia: {$stok} (Banyak)\n";
                            } elseif ($stok > 0) {
                                $booksFormatted .= "   ⚠️ Stok Tersedia: {$stok} (Terbatas)\n";
                            } else {
                                $booksFormatted .= "   ❌ Stok Tersedia: 0 (Kosong)\n";
                            }
                        }
                        
                        $booksFormatted .= "\n";
                    }
                    
                    $totalBooks = count($result['books']);
                    $booksFormatted .= "📝 *Total buku: {$totalBooks} buku tersedia*\n";
                } else {
                    $booksFormatted = "\n\n📚 **Pencarian Buku** 📚\n\n";
                    $booksFormatted .= "Maaf, tidak ditemukan buku yang sesuai dengan pencarian Anda. 😔\n";
                    $booksFormatted .= "Silakan coba kata kunci lain atau hubungi petugas perpustakaan untuk bantuan. 📞\n";
                }
            }

            // Step 4: Combine AI text with book data
            $finalResponse = $result['response_text'] . $booksFormatted;

            return [
                'success' => true,
                'intent' => $intentData['intent'],
                'response' => $finalResponse,
                'books_data' => $result['books'],
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