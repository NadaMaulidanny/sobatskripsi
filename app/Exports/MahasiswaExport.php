<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MahasiswaExport implements FromView, WithHeadings, WithMapping, WithColumnFormatting
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $query = Mahasiswa::with(['user', 'prodi']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                $this->startDate . ' 00:00:00', 
                $this->endDate . ' 23:59:59'
            ]);
        }

        return view('super.mahasiswa.export_pdf', [
            'mahasiswas' => $query->get(),
            'startDate'  => $this->startDate,
            'endDate'    => $this->endDate
        ]);
    }

    // Mengatur Header / Judul Kolom Tabel
    public function headings(): array
    {
        return ['NIM', 'Nama Mahasiswa', 'Email', 'Program Studi', 'Tanggal Terdaftar'];
    }

    // Memetakan data dari database ke kolom tabel
    public function map($mahasiswa): array
    {
        return [
            (string) $mahasiswa->nim, // Paksa menjadi string agar aman
            $mahasiswa->user->name ?? '-',
            $mahasiswa->user->email ?? '-',
            $mahasiswa->prodi->nama_prodi ?? '-',
            $mahasiswa->created_at->format('d-m-Y'),
        ];
    }

    /**
     * Mengatur format kolom khusus untuk Excel (.xlsx)
     * Ini yang bikin NIM aman tidak berubah jadi eksponen matematika (E+09) atau hilang angka 0 di depan.
     * Saat di-render ke PDF, format ini akan otomatis diabaikan secara aman oleh DomPDF.
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // Kolom A (NIM) diset sebagai TEXT murni
        ];
    }
}