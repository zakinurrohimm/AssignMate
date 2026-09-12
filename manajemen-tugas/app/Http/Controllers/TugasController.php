<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth; // [!] Penting: Memanggil library Autentikasi

class TugasController extends Controller
{
    public function index()
    {
        // 1. FILTER DATA: Hanya ambil tugas & matkul yang user_id-nya sama dengan akun yang login
        $tugas = Tugas::with('mataKuliah')->where('user_id', Auth::id())->get();
        $totalTugas = $tugas->count();
        $tugasSelesai = $tugas->where('status', 'Selesai')->count();
        $tugasBelum = $tugas->where('status', '!=', 'Selesai')->count(); // Menghitung tugas yang belum selesai
        
        $mata_kuliah = MataKuliah::where('user_id', Auth::id())->get();

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

            $interval = $sekarang->diff($deadline);
            $isTerlambat = $sekarang->gt($deadline);

            // 2. Jika Terlambat
            if ($isTerlambat) {
                $item->sisa_waktu = 'TERLAMBAT!';
                $item->badge_color = 'border border-rose-600 text-rose-500 bg-rose-950/50 animate-pulse font-bold tracking-widest';
            } 
            // 3. Jika Waktu Masih Ada
            else {
                $waktuSpesifik = [];
                if ($interval->days > 0) $waktuSpesifik[] = $interval->days . 'hri';
                if ($interval->h > 0) $waktuSpesifik[] = $interval->h . 'jm';
                if ($interval->i > 0) $waktuSpesifik[] = $interval->i . 'mnt';
                
                if (empty($waktuSpesifik)) {
                    $waktuSpesifik[] = $interval->s . 'dtk';
                }

                $item->sisa_waktu = implode(' ', $waktuSpesifik);
                
                $totalHours = $sekarang->diffInHours($deadline);
                
                if ($totalHours <= 24) {
                    // Hari H -> Merah Redup (Tanpa kedip)
                    $item->badge_color = 'border border-rose-500/50 text-rose-400 bg-rose-950/20 font-bold';
                } elseif ($totalHours <= 72) {
                    // 3 Hari ke bawah -> Kuning/Amber
                    $item->badge_color = 'border border-amber-500/50 text-amber-400 bg-amber-950/20';
                } else {
                    // Lebih dari 3 hari -> Netral/Abu-abu terminal
                    $item->badge_color = 'border border-zinc-700 text-zinc-400 bg-zinc-900/50'; 
                }
            }
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
        ]);

        $formattedDeadline = str_replace('T', ' ', $request->deadline);

        Tugas::create([
            'user_id' => Auth::id(),
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_tugas' => $request->nama_tugas,
            'deadline' => $formattedDeadline,
            'status' => 'Belum Dikerjakan' // Diubah agar sesuai dengan enum database
        ]);

        return redirect('/')->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    public function updateStatus($id)
    {
        $tugas = Tugas::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        // Sesuaikan dengan teks ENUM di database Anda ('Selesai' atau 'Selesai Dikerjakan')
        $tugas->update([
            'status' => 'Selesai' // Pastikan string ini cocok dengan pilihan ENUM status di database
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
                // Aturan unique khusus untuk user_id yang sedang login
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
        // Cari tugas berdasarkan ID dan pastikan itu milik user yang login
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
        ]);

        $tugas = Tugas::where('user_id', Auth::id())->findOrFail($id);
        $formattedDeadline = str_replace('T', ' ', $request->deadline);

        $tugas->update([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_tugas' => $request->nama_tugas,
            'deadline' => $formattedDeadline,
        ]);

        return redirect('/')->with('success', 'Parameter tugas berhasil diperbarui!');
    }

}