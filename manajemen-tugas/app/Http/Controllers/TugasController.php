<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\MataKuliah; // Tambahkan ini agar sistem mengenali tabel Mata Kuliah

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with('mataKuliah')->orderBy('deadline', 'asc')->get();

        // 1. Hitung Metrik untuk Dashboard Ringkasan
        $totalTugas = $tugas->count();
        $tugasSelesai = $tugas->where('status', 'Selesai')->count();
        $tugasBelum = $tugas->where('status', '!=', 'Selesai')->count();

        // 2. Logika Sisa Waktu & Status Otomatis
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
        
        // Kirim variabel metrik ke tampilan view
        return view('tugas.index', compact('tugas', 'totalTugas', 'tugasSelesai', 'tugasBelum'));
    }

    // Fungsi baru untuk menampilkan formulir tambah tugas
    public function create()
    {
        $mata_kuliah = MataKuliah::all(); // Ambil semua data mata kuliah untuk dropdown
        return view('tugas.create', compact('mata_kuliah'));
    }

    public function store(Request $request)
    {
        // Menyimpan data ke database
        Tugas::create([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_tugas' => $request->nama_tugas,
            'deadline' => $request->deadline,
            // Status dan prioritas akan otomatis terisi nilai default dari database
        ]);

        // Setelah sukses menyimpan, kembalikan user ke halaman awal
        return redirect('/');
    }

    // Fungsi untuk mengubah status tugas menjadi Selesai
    public function updateStatus($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->update(['status' => 'Selesai']);
        return redirect('/');
    }

    // Fungsi untuk menghapus tugas dari database
    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();
        return redirect('/');
    }
}