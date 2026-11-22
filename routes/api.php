<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('media', MediaController::class);
Route::apiResource('pengguna', PenggunaController::class);

// Custom action di luar CRUD standar: mengubah relasi koleksi media milik pengguna
Route::post('/pengguna/{pengguna}/koleksi', [PenggunaController::class, 'addToCollection']);
Route::delete('/pengguna/{pengguna}/koleksi/{media}', [PenggunaController::class, 'removeFromCollection']);
