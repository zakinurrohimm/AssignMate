<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk diisi
    protected $fillable = [
        'mata_kuliah_id', 'nama_tugas', 'deskripsi', 
        'tanggal_diberikan', 'deadline', 'prioritas', 'status', 'keterangan'
    ];

    // Relasi: 1 Tugas ini milik 1 Mata Kuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
}