<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Mahasiswa;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Generate tokens for existing approved mahasiswa without tokens
        $mahasiswas = Mahasiswa::where('status', 'approved')
            ->whereNull('referral_token')
            ->get();

        foreach ($mahasiswas as $mahasiswa) {
            $mahasiswa->referral_token = $this->generateReferralToken();
            $mahasiswa->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this
    }

    /**
     * Generate unique 6-digit referral token
     */
    private function generateReferralToken()
    {
        do {
            $token = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (Mahasiswa::where('referral_token', $token)->exists());

        return $token;
    }
};