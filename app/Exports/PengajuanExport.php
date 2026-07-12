<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengajuanExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    // Menerima parameter tanggal dari Controller
    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = Pengajuan::with(['mahasiswa.user', 'bidangStudi']);

        // Jalankan filter jika tanggal diisi oleh Super Admin
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                $this->startDate . ' 00:00:00', 
                $this->endDate . ' 23:59:59'
            ]);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'NIM', 
            'Nama Mahasiswa', 
            'Judul Skripsi', 
            'Deskripsi', 
            'Bidang Studi', 
            'Status Pengajuan', 
            'Catatan Dosen', 
            'Catatan Kaprodi',
            'Tanggal Diajukan'
        ];
    }

    public function map($pengajuan): array
    {
        return [
            $pengajuan->mahasiswa->nim ?? '-',
            $pengajuan->mahasiswa->user->name ?? '-',
            $pengajuan->judul,
            $pengajuan->deskripsi,
            $pengajuan->bidangStudi->nama ?? '-',
            ucfirst($pengajuan->status),
            $pengajuan->catatan_dosen ?? '-',
            $pengajuan->catatan_kaprodi ?? '-',
            $pengajuan->created_at->format('d-m-Y H:i'),
        ];
    }
}