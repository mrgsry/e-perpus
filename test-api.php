<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$baseUrl = env('APP_URL', 'http://localhost/sipusaka');

echo "Testing Book API Endpoints...\n\n";

// Test 1: GET /api/books/stats
echo "1. GET /api/books/stats\n";
$ch = curl_init($baseUrl . '/api/books/stats');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP: $httpCode\nResponse: " . $result . "\n\n";

// Test 2: POST /api/books/stock
echo "2. POST /api/books/stock (book_name=Kalkulus)\n";
$ch = curl_init($baseUrl . '/api/books/stock');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['book_name' => 'Kalkulus']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json']);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP: $httpCode\nResponse: " . $result . "\n\n";

// Test 3: POST /api/books/search
echo "3. POST /api/books/search (keyword=Matematika)\n";
$ch = curl_init($baseUrl . '/api/books/search');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['keyword' => 'Matematika', 'limit' => 3]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json']);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP: $httpCode\nResponse: " . $result . "\n\n";

// Test 4: POST /api/books/available
echo "4. POST /api/books/available (limit=3)\n";
$ch = curl_init($baseUrl . '/api/books/available');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['limit' => 3]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json']);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP: $httpCode\nResponse: " . $result . "\n\n";

// Test 5: POST /api/books/popular
echo "5. POST /api/books/popular (limit=3)\n";
$ch = curl_init($baseUrl . '/api/books/popular');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['limit' => 3]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json']);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP: $httpCode\nResponse: " . $result . "\n\n";

echo "Done!\n";
