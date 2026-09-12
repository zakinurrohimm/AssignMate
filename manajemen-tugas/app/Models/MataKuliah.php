<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'mata_kuliah';

    // Kolom yang diizinkan untuk diisi data
    protected $fillable = ['user_id','kode_matkul', 'nama_matkul', 'dosen', 'hari', 'jam', 'ruang'];

    // Relasi: 1 Mata Kuliah punya Banyak Tugas
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
}