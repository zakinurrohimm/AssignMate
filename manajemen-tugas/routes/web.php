<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\ProfileController;

// Rute AssignMate yang WAJIB LOGIN
Route::middleware(['auth'])->group(function () {
    
    // Halaman Utama AssignMate
    Route::get('/', [TugasController::class, 'index'])->name('dashboard');
    
    // Rute Mata Kuliah
    Route::get('/matkul/create', [TugasController::class, 'createMatkul'])->name('matkul.create');
    Route::post('/matkul', [TugasController::class, 'storeMatkul'])->name('matkul.store');
    Route::delete('/matkul/{id}', [TugasController::class, 'destroyMatkul'])->name('matkul.destroy');
    
    // Rute Tugas
    Route::get('/tugas/create', [TugasController::class, 'create'])->name('tugas.create');
    Route::post('/tugas', [TugasController::class, 'store'])->name('tugas.store');
    Route::match(['post', 'patch'], '/tugas/{id}/selesai', [TugasController::class, 'updateStatus'])->name('tugas.selesai');
    Route::delete('/tugas/{id}', [TugasController::class, 'destroy'])->name('tugas.destroy');

    // Rute Profil Bawaan Breeze (Jangan Dihapus)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
