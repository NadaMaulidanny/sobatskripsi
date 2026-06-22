<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'prodi_id'])]
class BidangStudi extends Model
{
    protected $table = 'bidang_studis';

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    /**
     * Dosen-dosen yang mengajar/ahli di bidang studi ini
     */
    public function dosens(): BelongsToMany
    {
        return $this->belongsToMany(Dosen::class, 'dosen_bidang_studis', 'bidang_studi_id', 'dosen_id')
                    ->withTimestamps();
    }

    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'bidang_studi_id');
    }
}