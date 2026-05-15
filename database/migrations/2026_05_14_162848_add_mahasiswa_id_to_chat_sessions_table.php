<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->nullable()->after('user_id');
            $table->string('mahasiswa_nim', 20)->nullable()->after('mahasiswa_id');
            $table->string('mahasiswa_nama', 100)->nullable()->after('mahasiswa_nim');
            
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->dropColumn(['mahasiswa_id', 'mahasiswa_nim', 'mahasiswa_nama']);
        });
    }
};