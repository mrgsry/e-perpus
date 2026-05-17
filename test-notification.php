<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;
use App\Notifications\MahasiswaApproved;

// Find a pending mahasiswa or create a test one
$mahasiswa = Mahasiswa::where('status', 'pending')->first();

if (!$mahasiswa) {
    echo "No pending mahasiswa found. Creating a test mahasiswa...\n";
    $mahasiswa = Mahasiswa::create([
        'nim' => 'TEST' . rand(1000, 9999),
        'nama' => 'Test Mahasiswa',
        'jurusan' => 'Test Jurusan',
        'email' => 'test@example.com', // Change this to your test email
        'status' => 'pending',
    ]);
    echo "Test mahasiswa created with ID: {$mahasiswa->id}\n";
}

echo "Testing notification for mahasiswa: {$mahasiswa->nama} ({$mahasiswa->email})\n";

// Generate referral token
$referralToken = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
$mahasiswa->referral_token = $referralToken;
$mahasiswa->status = 'approved';
$mahasiswa->save();

echo "Mahasiswa approved with referral token: {$referralToken}\n";

// Send notification
try {
    $mahasiswa->notify(new MahasiswaApproved($mahasiswa, $referralToken));
    echo "Notification sent successfully!\n";
    echo "Check the email: {$mahasiswa->email}\n";
} catch (Exception $e) {
    echo "Error sending notification: " . $e->getMessage() . "\n";
}