<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing mahasiswa records to have 'approved' status
        // This ensures that only NEW registrations will have 'pending' status
        DB::table('mahasiswas')
            ->whereNull('status')
            ->orWhere('status', '')
            ->update(['status' => 'approved']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data migration
        // as it's a one-time update for existing records
    }
};