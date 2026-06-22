<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'prodi_id', 'nim'])]
class Mahasiswa extends Model
{
    protected $table = 'mahasiswas';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    /**
     * Daftar pengajuan judul yang pernah dibuat mahasiswa ini
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'mahasiswa_id');
    }
}