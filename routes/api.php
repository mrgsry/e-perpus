<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\ReturnController;
use App\Http\Controllers\Api\FineController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Book API endpoints for AI integration
Route::prefix('books')->group(function () {
    Route::post('/stock', [BookController::class, 'getStock']);
    Route::post('/search', [BookController::class, 'search']);
    Route::post('/available', [BookController::class, 'getAvailable']);
    Route::post('/category', [BookController::class, 'getByCategory']);
    Route::post('/popular', [BookController::class, 'getPopular']);
    Route::get('/stats', [BookController::class, 'getStats']);
});

// Student API endpoints
Route::prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index']);
    Route::post('/', [StudentController::class, 'store']);
    Route::get('/{id}', [StudentController::class, 'show']);
    Route::put('/{id}', [StudentController::class, 'update']);
    Route::delete('/{id}', [StudentController::class, 'destroy']);
    Route::post('/search', [StudentController::class, 'search']);
});

// Loan API endpoints
Route::prefix('loans')->group(function () {
    Route::get('/', [LoanController::class, 'index']);
    Route::post('/', [LoanController::class, 'store']);
    Route::get('/{id}', [LoanController::class, 'show']);
    Route::put('/{id}', [LoanController::class, 'update']);
    Route::delete('/{id}', [LoanController::class, 'destroy']);
    Route::post('/search', [LoanController::class, 'search']);
    Route::post('/active', [LoanController::class, 'getActive']);
});

// Return API endpoints (Pengembalian)
Route::prefix('returns')->group(function () {
    Route::post('/process', [ReturnController::class, 'processReturn']);
    Route::post('/confirm-payment/{id}', [ReturnController::class, 'confirmPayment']);
    Route::get('/', [ReturnController::class, 'index']);
    Route::get('/{id}', [ReturnController::class, 'show']);
});

// Fine API endpoints (Denda)
Route::prefix('fines')->group(function () {
    Route::get('/', [FineController::class, 'index']);
    Route::get('/{id}', [FineController::class, 'show']);
    Route::post('/calculate', [FineController::class, 'calculateFine']);
    Route::post('/pay', [FineController::class, 'pay']);
    Route::post('/unpaid', [FineController::class, 'getUnpaid']);
    Route::get('/stats', [FineController::class, 'statistics']);
});