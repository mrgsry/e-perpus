<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Publik\KatalogController;
use App\Http\Controllers\Publik\PinjamController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\DendaController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Public\ChatController as PublicChatController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;

// =====================
// ROUTE PUBLIK
// =====================
Route::get('/', [KatalogController::class, 'index'])->name('publik.katalog');
Route::get('/buku/{id}', [KatalogController::class, 'show'])->name('publik.buku.show');
Route::get('/katalog/search', [KatalogController::class, 'search'])->name('publik.katalog.search');
Route::get('/ebook/baca/{id}', [KatalogController::class, 'ebookReader'])->name('publik.ebook.baca');
Route::get('/ebook/stream/{id}', [KatalogController::class, 'streamPdf'])->name('ebook.stream');

Route::get('/pinjam', [PinjamController::class, 'index'])->name('publik.pinjam.form');
Route::post('/pinjam', [PinjamController::class, 'store'])->name('publik.pinjam');
Route::get('/pinjam/get-mahasiswa/{nim}', [PinjamController::class, 'getMahasiswaByNim'])->name('publik.get-mahasiswa');
Route::get('/pinjam/cek-nim', [PinjamController::class, 'cekNim'])->name('publik.cek-nim');
Route::get('/pinjam/validate-token', [PinjamController::class, 'validateToken'])->name('publik.validate-token');
Route::get('/pinjam/get-qr', [PinjamController::class, 'getPeminjamanByQr'])->name('publik.pinjam.get-qr');
Route::get('/pinjam/konfirmasi/{booking_id}', [PinjamController::class, 'konfirmasi'])->name('publik.konfirmasi');
Route::get('/cek-status', [PinjamController::class, 'cekStatus'])->name('publik.cek-status');
Route::post('/cek-status', [PinjamController::class, 'cekStatusPost'])->name('publik.cek-status.post');

Route::get('/cek-status/ajax', [PinjamController::class, 'cekStatusAjax'])->name('publik.cek-status.ajax');
Route::get('/register', [\App\Http\Controllers\Public\RegisterController::class, 'create'])->name('publik.register.form');
Route::post('/register', [\App\Http\Controllers\Public\RegisterController::class, 'store'])->name('publik.register.store');

// =====================
// ROUTE AUTH ADMIN
// =====================
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// =====================
// ROUTE ADMIN (protected)
// =====================
Route::prefix('admin')->middleware('admin.auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('admin.buku.index');
    Route::post('/buku', [BukuController::class, 'store'])->name('admin.buku.store');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('admin.buku.update');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('admin.buku.destroy');
    Route::get('/buku/export/pdf', [BukuController::class, 'exportPdf'])->name('admin.buku.export-pdf');
    Route::get('/buku/export/excel', [BukuController::class, 'exportExcel'])->name('admin.buku.export-excel');

    // Mahasiswa
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('admin.mahasiswa.index');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('admin.mahasiswa.store');
    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('admin.mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('admin.mahasiswa.destroy');
    Route::post('/mahasiswa/{id}/approve', [MahasiswaController::class, 'approve'])->name('admin.mahasiswa.approve');
    Route::post('/mahasiswa/{id}/reject', [MahasiswaController::class, 'reject'])->name('admin.mahasiswa.reject');
    Route::post('/mahasiswa/{id}/resend-email', [MahasiswaController::class, 'resendEmail'])->name('admin.mahasiswa.resend-email');

    // Peminjaman
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
    Route::get('/peminjaman/scan-qr', [PeminjamanController::class, 'scanQr'])->name('admin.peminjaman.scan-qr');
    Route::post('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('admin.peminjaman.approve');
    Route::post('/peminjaman/{id}/tolak', [PeminjamanController::class, 'tolak'])->name('admin.peminjaman.tolak');
    Route::get('/peminjaman/export/pdf', [PeminjamanController::class, 'exportPdf'])->name('admin.peminjaman.export-pdf');
    Route::get('/peminjaman/export/excel', [PeminjamanController::class, 'exportExcel'])->name('admin.peminjaman.export-excel');

    // Pengembalian
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('admin.pengembalian.index');
    Route::get('/pengembalian/scan-qr', [PengembalianController::class, 'scanQr'])->name('admin.pengembalian.scan-qr');
    Route::get('/pengembalian/get-by-booking', [PengembalianController::class, 'getByBooking'])->name('admin.pengembalian.get-by-booking');
    Route::post('/pengembalian/{id}/proses', [PengembalianController::class, 'proses'])->name('admin.pengembalian.proses');
    Route::post('/pengembalian/{id}/approve', [PengembalianController::class, 'approve'])->name('admin.pengembalian.approve');

    // Denda
    Route::get('/denda', [DendaController::class, 'index'])->name('admin.denda.index');
    Route::post('/denda/{id}/lunas', [DendaController::class, 'tandaiLunas'])->name('admin.denda.lunas');
    Route::get('/denda/export/pdf', [DendaController::class, 'exportPdf'])->name('admin.denda.export-pdf');
    Route::get('/denda/export/excel', [DendaController::class, 'exportExcel'])->name('admin.denda.export-excel');

    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('admin.history.index');
    Route::get('/history/export/pdf', [HistoryController::class, 'exportPdf'])->name('admin.history.export-pdf');
    Route::get('/history/export/excel', [HistoryController::class, 'exportExcel'])->name('admin.history.export-excel');

    // Chat Admin
    Route::get('/chat', [AdminChatController::class, 'index'])->name('admin.chat.index');
    Route::get('/chat/{sessionId}', [AdminChatController::class, 'show'])->name('admin.chat.show');
    Route::post('/chat/send', [AdminChatController::class, 'sendMessage'])->name('admin.chat.send');
    Route::post('/chat/{sessionId}/close', [AdminChatController::class, 'closeSession'])->name('admin.chat.close');
    Route::get('/chat/{sessionId}/messages', [AdminChatController::class, 'getNewMessages'])->name('admin.chat.messages');
});

// =====================
// ROUTE CHAT PUBLIK (untuk mahasiswa yang NIM-nya terdaftar)
// =====================
Route::post('/chat/verify-nim', [PublicChatController::class, 'verifyNim'])->name('chat.verify-nim');
Route::post('/chat/send-message', [PublicChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/messages', [PublicChatController::class, 'getMessages'])->name('chat.messages');
