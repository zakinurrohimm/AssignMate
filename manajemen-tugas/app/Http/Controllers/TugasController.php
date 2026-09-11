<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\MataKuliah;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with('mataKuliah')->get();
        $totalTugas = $tugas->count();
        $tugasSelesai = $tugas->where('status', 'Selesai')->count();
        $tugasBelum = $tugas->where('status', 'Belum')->count();
        $mata_kuliah = MataKuliah::all();

        // Logika Sisa Waktu & Status Otomatis
        foreach ($tugas as $item) {
            $sekarang = \Carbon\Carbon::now();
            $deadline = \Carbon\Carbon::parse($item->deadline);
            
            if ($item->status == 'Selesai') {
                $item->badge_color = 'bg-emerald-100 text-emerald-700';
                $item->sisa_waktu = 'Selesai';
                continue;
            }

            $selisihJam = $sekarang->diffInHours($deadline, false);

            if ($selisihJam < 0) {
                $item->sisa_waktu = 'Terlambat!';
                $item->badge_color = 'bg-rose-100 text-rose-700 animate-pulse';
            } elseif ($selisihJam <= 24) {
                $item->sisa_waktu = 'Kurang dari 24 Jam!';
                $item->badge_color = 'bg-orange-100 text-orange-700';
            } elseif ($selisihJam <= 72) {
                $item->sisa_waktu = '1 - 3 Hari lagi';
                $item->badge_color = 'bg-amber-100 text-amber-700';
            } else {
                $item->sisa_waktu = $sekarang->diffInDays($deadline) . ' Hari lagi';
                $item->badge_color = 'bg-blue-100 text-blue-700';
            }
        }
        
        // Return view HANYA SEKALI di paling bawah dengan variabel lengkap
        return view('tugas.index', compact('tugas', 'totalTugas', 'tugasSelesai', 'tugasBelum', 'mata_kuliah'));
    }

public function create()
{
    $mataKuliah = \App\Models\MataKuliah::all();
    return view('tugas.create', compact('mataKuliah'));
}

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required',
            'nama_tugas' => 'required',
            'deadline' => 'required|date',
        ]);

        try {
            Tugas::create($request->all());
            return redirect('/')->with('success', 'Tugas baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan tugas: ' . $e->getMessage())->withInput();
        }
    }

    // Fungsi untuk mengubah status tugas menjadi Selesai
    public function updateStatus($id)
    {
    $tugas = \App\Models\Tugas::findOrFail($id);
    $tugas->status = 'Selesai';
    $tugas->save();

    return redirect()->back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    // Fungsi untuk menghapus tugas dari database
    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();
        return redirect('/');
    }

    // Menampilkan form tambah mata kuliah
    public function createMatkul()
    {
        $mata_kuliah = MataKuliah::all();
        return view('tugas.create_matkul', compact('mata_kuliah'));
    }

    // Menyimpan mata kuliah baru ke database
    public function storeMatkul(Request $request)
    {
        $request->validate([
            'kode_matkul' => 'required|unique:mata_kuliah,kode_matkul',
            'nama_matkul' => 'required',
        ], [
            'kode_matkul.unique' => 'Kode mata kuliah ini sudah terdaftar, silakan gunakan kode lain.',
        ]);

            try {
            MataKuliah::create([
                'kode_matkul' => $request->kode_matkul,
                'nama_matkul' => $request->nama_matkul,
                'dosen' => $request->dosen,
            ]);

            // Menggunakan redirect()->back() agar tetap di halaman form
            return redirect()->back()->with('success', 'Mata kuliah baru berhasil ditambahkan!');
            } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan mata kuliah: ' . $e->getMessage())->withInput();
            }
    }
    
    public function destroyMatkul($id)
    {
        try {
            $matkul = MataKuliah::findOrFail($id);
            $matkul->delete();
            return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus! Pastikan tidak ada tugas aktif yang masih menggunakan mata kuliah ini.');
        }
    }
}