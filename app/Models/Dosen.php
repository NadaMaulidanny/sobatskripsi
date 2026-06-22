<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['user_id', 'prodi_id', 'nidn', 'kuota'])]
class Dosen extends Model
{
    protected $table = 'dosens';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    /**
     * Bidang studi yang dikuasai dosen
     */
    public function bidangStudis(): BelongsToMany
    {
        return $this->belongsToMany(BidangStudi::class, 'dosen_bidang_studis', 'dosen_id', 'bidang_studi_id');
        // Hapus baris ->withTimestamps();
    }

    public function bimbinganPengajuan(): BelongsToMany
    {
        return $this->belongsToMany(Pengajuan::class, 'pembimbing', 'dosen_id', 'pengajuan_id')
                    ->using(Pembimbing::class)
                    ->withPivot('status') // sesuaikan nama kolom di DB Anda, jika masih 'status' isinya pembimbing1/2
                    ->withTimestamps();
    }
}