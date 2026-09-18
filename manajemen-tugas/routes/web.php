<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\ProfileController;

// Wajib dipanggil untuk fitur Notifikasi Email
use Illuminate\Support\Facades\Mail;
use App\Models\Tugas;
use Carbon\Carbon;

// Rute AssignMate yang WAJIB LOGIN
Route::middleware(['auth', 'verified'])->group(function () {
    
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
    Route::get('/tugas/{id}/edit', [TugasController::class, 'edit']);
    Route::put('/tugas/{id}', [TugasController::class, 'update']);

    // Rute Profil Bawaan Breeze (Jangan Dihapus)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/verified-success', function () {
        return view('auth.verified-success');
    })->name('verified.success');
});

// =========================================================================
// RUTE TRIGGER ALARM (Ditaruh di luar auth agar bisa diakses cron-job.org)
// =========================================================================
Route::get('/api/trigger-reminder', function (\Illuminate\Http\Request $request) {
    // 1. CEK KUNCI RAHASIA (BYPASS ANTI-BOT)
    if ($request->query('key') !== 'assignmate123') {
        return response("SYSTEM ERROR: UNAUTHORIZED.", 401);
    }

    // 2. JIKA KUNCI BENAR, LANJUT EKSEKUSI
    // Ambil tanggal hari ini dan H-3
    $hariIni = Carbon::today();
    $hMin3 = Carbon::today()->addDays(3);
    
    // Cari tugas mendesak beserta data User dan Mata Kuliahnya
    $tugasMendesak = Tugas::with(['user', 'mataKuliah'])
                          ->where('status', '!=', 'Selesai')
                          ->where(function($query) use ($hariIni, $hMin3) {
                              $query->whereDate('deadline', $hariIni)
                                    ->orWhereDate('deadline', $hMin3);
                          })
                          ->get();

    $jumlahTerkirim = 0;

    if ($tugasMendesak->count() > 0) {
        foreach ($tugasMendesak as $t) {
            // Pengamanan: Lewati jika user tidak punya email valid
            if (!$t->user || !$t->user->email) {
                continue; 
            }

            // Tentukan ini H-3 atau Hari H
            $tglDeadline = Carbon::parse($t->deadline)->startOfDay();
            
            if ($tglDeadline->equalTo($hariIni)) {
                $statusWaktu = "HARI INI (DEADLINE!)";
                $tipeAlert = "CRITICAL_WARNING";
            } else {
                $statusWaktu = "H-3 (TIGA HARI LAGI)";
                $tipeAlert = "YELLOW_ALERT";
            }

            // Format Pesan Email ala Terminal
            $pesan = "// SYSTEM_ALERT: $tipeAlert!\n\n"
                   . "OPERATOR    : " . strtoupper($t->user->name) . "\n"
                   . "MATA KULIAH : " . strtoupper($t->mataKuliah->nama_matkul ?? 'TIDAK ADA') . "\n"
                   . "TUGAS       : [" . strtoupper($t->nama_tugas) . "]\n"
                   . "DEADLINE    : " . $t->deadline . "\n\n"
                   . "STATUS: Batas waktu tersisa $statusWaktu.\n"
                   . "// HARAP SEGERA DIEKSEKUSI.";

            // Eksekusi Kirim Email ke masing-masing User
            Mail::raw($pesan, function ($message) use ($t, $tipeAlert) {
                $message->to($t->user->email)
                        ->subject("[$tipeAlert] TUGAS: " . strtoupper($t->nama_tugas));
            });

            $jumlahTerkirim++;
        }
        
        return "SYSTEM: " . $jumlahTerkirim . " REMINDERS EXECUTED TO USERS.";
    }

    return "SYSTEM: STANDBY. NO H-3 OR D-DAY ASSIGNMENTS.";
});

require __DIR__.'/auth.php';