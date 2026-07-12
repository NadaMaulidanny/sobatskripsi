<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengajuan;
use App\Models\Mahasiswa;

class DosenDashboardController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;

        if (!$dosen) {
            return view('dosen.dashboard', [
                'pengajuans' => collect(),
                'totalMahasiswa' => 0,
                'totalBidang' => 0,
                'totalPending' => 0
            ]);
        }

        $bidangStudiIds = $dosen->bidangStudis()->pluck('bidang_studi_id')->toArray();

        $pengajuans = Pengajuan::whereIn('bidang_studi_id', $bidangStudiIds)
                                ->with(['mahasiswa.user', 'bidangStudi'])
                                ->latest()
                                ->get();

        // 3. Hitung data untuk statistik Card
        $totalBidang = $pengajuans->count(); // Total seluruh pengajuan di bidangnya
        $totalPending = $pengajuans->where('status', 'menunggu')->count(); // Yang statusnya menunggu

        // 4. Hitung mahasiswa bimbingan aktif (yang status pengajuannya sudah disetujui)
        // Hubungkan dengan relasi pembimbing di tabel pivot pengajuan_dosen kamu jika ada
        $totalMahasiswa = $pengajuans->where('status', 'disetujui')->count(); 

        // ... kode pengambilan data $pengajuans di atas ...

        // Hitung mahasiswa yang sudah disetujui
        $totalMahasiswaBimbingan = $pengajuans->where('status', 'disetujui')->count(); 

        // AMBIL LANGSUNG DARI KOLOM DI TABEL DOSEN
        // Ganti 'kuota_maksimal' sesuai nama kolom aslimu di database
        $kuotaMaksimal = $dosen->kuota_maksimal ?? 10; // Nilai default 10 jika kolom kosong

        // Hitung persentase kuota yang terpakai
        $persentaseKuota = ($totalMahasiswaBimbingan / max(1, $kuotaMaksimal)) * 100;
        $persentaseKuota = min($persentaseKuota, 100);

        return view('dosen.dashboard', compact(
            'pengajuans', 
            'totalMahasiswa',
            'totalBidang',
            'totalPending',
            'totalMahasiswaBimbingan',
            'kuotaMaksimal',
            'persentaseKuota'
        ));
    }
}
