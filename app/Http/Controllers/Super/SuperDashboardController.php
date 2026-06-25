<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;

class SuperDashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Total Mahasiswa di tabel induk mahasiswas
        $totalMahasiswa = Mahasiswa::count();

        // 2. Hitung Mahasiswa yang aktif (is_verified = true di tabel users)
        $mhsAktif = Mahasiswa::whereHas('user', function ($query) {
            $query->where('is_verified', true);
        })->count();

        // 3. Hitung Total Dosen Pembimbing
        $totalDosen = Dosen::count();

        // 4. Hitung Total Seluruh Usulan Judul yang Masuk
        $totalPengajuan = Pengajuan::count();

        // 5. Ambil 5 Mahasiswa yang baru didaftarkan (untuk tabel Ringkasan di Kiri)
        $mahasiswaTerbaru = Mahasiswa::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 6. Ambil 5 Dosen dengan kuota paling sedikit/habis (untuk Monitoring di Kanan)
        $dosenKuotaKritis = Dosen::with('user')
            ->orderBy('kuota', 'asc')
            ->take(5)
            ->get();

        // Kirim semua variabel ke view admin.dashboard
        return view('super.dashboard', compact(
            'totalMahasiswa', 
            'mhsAktif', 
            'totalDosen', 
            'totalPengajuan',
            'mahasiswaTerbaru',
            'dosenKuotaKritis'
        ));
    }
}