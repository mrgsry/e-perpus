<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing 9router AI Connection...\n\n";

try {
    $service = new \App\Services\NineRouterService();

    echo "1. Testing simple chat...\n";
    $response = $service->chat('Halo');
    echo "Response: " . ($response ?? 'NULL') . "\n\n";

    echo "2. Testing book query (existing book)...\n";
    $response2 = $service->chat('Apakah buku Kalkulus tersedia?');
    echo "Response: " . ($response2 ?? 'NULL') . "\n\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
