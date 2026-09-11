<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TugasController;

// Mengarahkan halaman utama web langsung ke TugasController
Route::get('/', [TugasController::class, 'index']);
Route::get('/tugas/create', [TugasController::class, 'create']);
Route::post('/tugas', [TugasController::class, 'store']); // Untuk menyimpan data
Route::patch('/tugas/{id}/selesai', [TugasController::class, 'updateStatus']); // Ubah status jadi selesai
Route::delete('/tugas/{id}', [TugasController::class, 'destroy']); // Hapus tugas
Route::get('/matkul/create', [TugasController::class, 'createMatkul']);
Route::post('/matkul', [TugasController::class, 'storeMatkul']);
Route::delete('/matkul/{id}', [TugasController::class, 'destroyMatkul']);
Route::patch('/tugas/{id}/status', [TugasController::class, 'updateStatus']);
Route::delete('/matkul/{id}', [TugasController::class, 'destroyMatkul'])->name('matkul.destroy');
