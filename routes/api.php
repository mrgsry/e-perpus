<?php

use App\Http\Controllers\Api\BookController;
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
