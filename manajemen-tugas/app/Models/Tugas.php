<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas'; // Sesuaikan kalau nama tabelmu beda

    protected $fillable = [
        'user_id', 
        'mata_kuliah_id', 
        'nama_tugas', 
        'deskripsi',
        'prioritas',
        'jenis_tugas', 
        'deadline', 
        'status'
    ];

    // INI RELASI YANG TADI ERROR KARENA KELUPAAN
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Pastikan relasi mataKuliah juga ada (kalau belum, tambahin sekalian)
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}