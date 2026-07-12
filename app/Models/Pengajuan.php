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

    protected $casts = [
        'catatan_dosen' => 'array',
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

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('mahasiswa', function ($qMhs) use ($search) {
                $qMhs->where(function ($qInternal) use ($search) {
                    $qInternal->where('nim', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($qUser) use ($search) {
                                $qUser->where('name', 'like', '%' . $search . '%');
                            });
                });
            });
        }

        // 2. FILTER JUDUL SKRIPSI
        if (!empty($filters['search_judul'])) {
            $query->where('judul', 'like', '%' . $filters['search_judul'] . '%');
        }

        // 3. FILTER STATUS
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 4. FILTER BIDANG STUDI
        if (!empty($filters['bidang_studi_id'])) {
            $query->where('bidang_studi_id', $filters['bidang_studi_id']);
        }

        return $query;
    }
}