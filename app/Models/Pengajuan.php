<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pengajuan extends Model
{
    protected $table = 'pengajuans'; // Menegaskan nama tabel

    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'deskripsi',
        'bidang_studi_id',
        'status',
        'catatan_dosen',
        'catatan_kaprodi',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function bidangStudi(): BelongsTo
    {
        return $this->belongsTo(BidangStudi::class, 'bidang_studi_id');
    }

    public function pembimbingDosens(): BelongsToMany
    {
        return $this->belongsToMany(Dosen::class, 'pembimbing', 'pengajuan_id', 'dosen_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}