<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;

// Tambahan wajib untuk fitur Lazy Cron / Pengirim Email
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        // =========================================================================
        // FITUR LAZY CRON: JALANIN REMINDER EMAIL 1X SEHARI
        // =========================================================================
        if (!Cache::has('reminder_dikirim_hari_ini')) {
            $hariIni = Carbon::today();
            $hMin3 = Carbon::today()->addDays(3);
            
            // Cari tugas mendesak untuk SEMUA user di database
            $tugasMendesak = Tugas::with(['user', 'mataKuliah'])
                                  ->where('status', '!=', 'Selesai')
                                  ->where(function($query) use ($hariIni, $hMin3) {
                                      $query->whereDate('deadline', $hariIni)
                                            ->orWhereDate('deadline', $hMin3);
                                  })
                                  ->get();

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

                    // Format Pesan Email
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
                }
            }
            
            // KUNCI GEMBOK: Tandai kalau hari ini udah ngirim email.
            // Fitur ini otomatis expired / ke-reset nanti malam jam 23:59:59.
            Cache::put('reminder_dikirim_hari_ini', true, Carbon::now()->endOfDay());
        }
        // =========================================================================


        // Hitung total statistik langsung dari database
        $totalTugas = Tugas::where('user_id', Auth::id())->count();
        $tugasSelesai = Tugas::where('user_id', Auth::id())->where('status', 'Selesai')->count();
        $tugasBelum = Tugas::where('user_id', Auth::id())->where('status', '!=', 'Selesai')->count();
        
        $mata_kuliah = MataKuliah::where('user_id', Auth::id())->get();

        // 1. Inisialisasi Query untuk tugas user yang login
        $query = Tugas::with('mataKuliah')->where('user_id', Auth::id());

        // 2. FITUR SEARCH (Pencarian Nama Tugas)
        if ($request->filled('search')) {
            $query->where('nama_tugas', 'like', '%' . $request->search . '%');
        }

        // 3. FITUR FILTER (Berdasarkan Mata Kuliah)
        if ($request->filled('matkul_id')) {
            $query->where('mata_kuliah_id', $request->matkul_id);
        }
        
        // 4. FITUR FILTER (Berdasarkan Status Selesai/Belum)
        if ($request->filled('status')) {
            if ($request->status === 'Belum Selesai') {
                $query->where('status', '!=', 'Selesai');
            } else {
                $query->where('status', $request->status);
            }
        }

        // 5. FITUR FILTER PRIORITAS (Tinggi / Sedang / Rendah)
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        // 6. FITUR FILTER URGENSI WAKTU / DEADLINE
        if ($request->filled('urgency')) {
            $now = Carbon::now();
            if ($request->urgency === 'critical') {
                $query->where('status', '!=', 'Selesai')
                      ->where('deadline', '>=', $now)
                      ->where('deadline', '<=', $now->copy()->addHours(24));
            } elseif ($request->urgency === 'urgent') {
                $query->where('status', '!=', 'Selesai')
                      ->where('deadline', '>=', $now)
                      ->where('deadline', '<=', $now->copy()->addDays(3));
            } elseif ($request->urgency === 'overdue') {
                $query->where('status', '!=', 'Selesai')
                      ->where('deadline', '<', $now);
            }
        }

        // 5. FITUR SORTING: Selesai selalu di bawah, sisanya diurutkan berdasarkan deadline ASC/DESC
        $sort = $request->get('sort', 'asc');
        $query->orderByRaw("CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END ASC")
              ->orderBy('deadline', $sort);

        // 6. FITUR PAGINATION (Batasi 15 tugas per halaman)
        $tugas = $query->paginate(15)->appends($request->query());

        // Logika Sisa Waktu & Status Otomatis
        foreach ($tugas as $item) {
            $sekarang = \Carbon\Carbon::now();
            $deadline = \Carbon\Carbon::parse($item->deadline);
            
            // 1. Jika tugas selesai
            if ($item->status == 'Selesai') {
                $item->badge_color = 'border border-emerald-500/50 text-emerald-500 bg-emerald-950/30';
                $item->sisa_waktu = 'Selesai';
                continue;
            }

            $isTerlambat = $sekarang->gt($deadline);

            // 2. Jika Terlambat
            if ($isTerlambat) {
                $item->sisa_waktu = 'TERLAMBAT!';
                $item->badge_color = 'border border-rose-600 text-rose-500 bg-rose-950/50 animate-pulse font-bold tracking-widest';
            } 
            // 3. Jika Waktu Masih Ada
            else {
                $diffInDays = (int) $sekarang->diffInDays($deadline);
                $diffInHours = (int) $sekarang->diffInHours($deadline);
                $diffInMinutes = (int) $sekarang->diffInMinutes($deadline);
                $diffInSeconds = (int) $sekarang->diffInSeconds($deadline);

                if ($diffInDays > 0) {
                    $item->sisa_waktu = $diffInDays . ' HARI LAGI';
                } elseif ($diffInHours > 0) {
                    $item->sisa_waktu = $diffInHours . ' JAM LAGI';
                } elseif ($diffInMinutes > 0) {
                    $item->sisa_waktu = $diffInMinutes . ' MENIT LAGI';
                } else {
                    $item->sisa_waktu = max(0, $diffInSeconds) . ' DETIK LAGI';
                }
                
                $totalHours = $sekarang->diffInHours($deadline);
                
                if ($totalHours <= 24) {
                    $item->badge_color = 'border border-rose-500/50 text-rose-400 bg-rose-950/20 font-bold';
                } elseif ($totalHours <= 72) {
                    $item->badge_color = 'border border-amber-500/50 text-amber-400 bg-amber-950/20';
                } else {
                    $item->badge_color = 'border border-zinc-700 text-zinc-400 bg-zinc-900/50'; 
                }
            }
        }
        
        if ($request->ajax()) {
            return view('tugas.partials.table_content', compact('tugas'));
        }

        return view('tugas.index', compact('tugas', 'totalTugas', 'tugasSelesai', 'tugasBelum', 'mata_kuliah'));
    }

    public function create()
    {
        $mataKuliah = MataKuliah::where('user_id', Auth::id())->get();
        return view('tugas.create', compact('mataKuliah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'nama_tugas' => 'required|string|max:255',
            'deadline' => 'required',
            'prioritas' => 'nullable|in:Tinggi,Sedang,Rendah',
            'deskripsi' => 'nullable|string',
        ]);

        $formattedDeadline = str_replace('T', ' ', $request->deadline);

        Tugas::create([
            'user_id' => Auth::id(),
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_tugas' => $request->nama_tugas,
            'deadline' => $formattedDeadline,
            'prioritas' => $request->prioritas ?? 'Sedang',
            'deskripsi' => $request->deskripsi,
            'status' => 'Belum Dikerjakan'
        ]);

        return redirect('/')->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    public function updateStatus($id)
    {
        $tugas = Tugas::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $tugas->update([
            'status' => 'Selesai'
        ]);

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui menjadi Selesai.');
    }

    public function destroy($id)
    {
        $tugas = Tugas::where('user_id', Auth::id())->findOrFail($id);
        $tugas->delete();
        return redirect('/');
    }

    public function createMatkul()
    {
        $mata_kuliah = MataKuliah::where('user_id', Auth::id())->get();
        return view('tugas.create_matkul', compact('mata_kuliah'));
    }

    public function storeMatkul(Request $request)
    {
        $request->validate([
            'kode_matkul' => [
                'required',
                \Illuminate\Validation\Rule::unique('mata_kuliah')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'nama_matkul' => 'required',
        ], [
            'kode_matkul.unique' => 'Kode mata kuliah ini sudah terdaftar di akun Anda.'
        ]);

        try {
            MataKuliah::create([
                'user_id' => Auth::id(),
                'kode_matkul' => $request->kode_matkul,
                'nama_matkul' => $request->nama_matkul,
                'dosen' => $request->dosen,
            ]);

            return redirect()->back()->with('success', 'Mata kuliah baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan mata kuliah: ' . $e->getMessage())->withInput();
        }
    }
    
    public function destroyMatkul($id)
    {
        try {
            $matkul = MataKuliah::where('user_id', Auth::id())->findOrFail($id);
            $matkul->delete();
            return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus! Pastikan tidak ada tugas aktif yang masih menggunakan mata kuliah ini.');
        }
    }

    public function edit($id)
    {
        $tugas = Tugas::where('user_id', Auth::id())->findOrFail($id);
        $mataKuliah = MataKuliah::where('user_id', Auth::id())->get();
        
        return view('tugas.edit', compact('tugas', 'mataKuliah'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'nama_tugas' => 'required|string|max:255',
            'deadline' => 'required',
            'prioritas' => 'nullable|in:Tinggi,Sedang,Rendah',
            'deskripsi' => 'nullable|string',
        ]);

        $tugas = Tugas::where('user_id', Auth::id())->findOrFail($id);
        $formattedDeadline = str_replace('T', ' ', $request->deadline);

        $updateData = [
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_tugas' => $request->nama_tugas,
            'deadline' => $formattedDeadline,
        ];

        if ($request->filled('prioritas')) {
            $updateData['prioritas'] = $request->prioritas;
        }
        if ($request->has('deskripsi')) {
            $updateData['deskripsi'] = $request->deskripsi;
        }

        $tugas->update($updateData);

        return redirect('/')->with('success', 'Parameter tugas berhasil diperbarui!');
    }
}