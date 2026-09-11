<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TugasController;

// Mengarahkan halaman utama web langsung ke TugasController
Route::get('/', [TugasController::class, 'index']);
Route::get('/tugas/create', [TugasController::class, 'create']);
Route::post('/tugas', [TugasController::class, 'store']); // Untuk menyimpan data
Route::patch('/tugas/{id}/selesai', [TugasController::class, 'updateStatus']); // Ubah status jadi selesai
Route::delete('/tugas/{id}', [TugasController::class, 'destroy']); // Hapus tugas