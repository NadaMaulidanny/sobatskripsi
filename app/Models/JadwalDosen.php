<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDosen extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika Laravel tidak sengaja mendeteksinya sebagai bentuk jamak otomatis yang salah
    protected $table = 'jadwal_dosens';

    // Kolom yang diizinkan untuk diisi massal
    protected $fillable = [
        'user_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kuota_mahasiswa'
    ];

    /**
     * Relasi Balik ke Model User (Dosen)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}