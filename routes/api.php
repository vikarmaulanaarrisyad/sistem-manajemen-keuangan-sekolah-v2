<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JurnalController;
use App\Http\Controllers\API\PresensiSiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/email/verify', [AuthController::class, 'sendEmailVerification']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route::middleware('role:guru')->group(function () {
    //     // Jurnal
    Route::get('/jurnal', [JurnalController::class, 'index']);
    Route::post('/jurnal', [JurnalController::class, 'store']);
    Route::put('/jurnal/{id}', [JurnalController::class, 'update']);
    Route::delete('/jurnal/{id}', [JurnalController::class, 'destroy']);
    // });

    Route::resource('/siswa-presensi', PresensiSiswaController::class);
});
