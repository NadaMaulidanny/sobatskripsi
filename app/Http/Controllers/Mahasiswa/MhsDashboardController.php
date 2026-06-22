<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSkripsi; // Pastikan nanti nama Model sesuai dengan tabel pengajuan Anda
use Illuminate\Http\Request;
use Illuminate\View\View;

class MhsDashboardController extends Controller
{
    /**
     * Menampilkan halaman utama Dashboard Mahasiswa
     */
    public function index(Request $request): View
    {
        // 1. Ambil data user beserta relasi mahasiswanya
        $user = $request->user();
        $mahasiswa = $user->mahasiswa;

        // 2. Ambil riwayat pengajuan judul milik mahasiswa ini (Urutkan dari yang terbaru)
        // Kita gunakan try-catch / optional agar tidak langsung eror sebelum migrasi tabel dibuat
        $pengajuans = [];
        $pengajuanTerakhir = null;

        if ($mahasiswa) {
            // Jika Anda sudah membuat model & tabel PengajuanSkripsi nanti, aktifkan baris ini:
            // $pengajuans = PengajuanSkripsi::where('mahasiswa_id', $mahasiswa->id)
            //     ->orderBy('created_at', 'desc')
            //     ->get();
            //
            // $pengajuanTerakhir = $pengajuans->first();
        }

        return view('mahasiswa.dashboard', compact('user', 'mahasiswa', 'pengajuans', 'pengajuanTerakhir'));
    }
}