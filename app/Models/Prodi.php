<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_prodi'])]
class Prodi extends Model
{
    protected $table = 'prodis';

    public function dosens(): HasMany
    {
        return $this->hasMany(Dosen::class, 'prodi_id');
    }

    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id');
    }

    public function bidangStudis(): HasMany
    {
        return $this->hasMany(BidangStudi::class, 'prodi_id');
    }
}