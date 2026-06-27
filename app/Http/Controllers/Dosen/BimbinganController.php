<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Logbook;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimbinganController extends Controller
{
    private function getDosen()
    {
        return Dosen::where('user_id', Auth::id())->firstOrFail();
    }
    public function index()
    {
        $dosen = $this->getDosen();
        $mahasiswas = Mahasiswa::with(['user', 'prodi'])
            ->whereHas('pengajuans.pembimbingDosens', function($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id)
                      ->whereIn('pembimbing.status', ['pembimbing1', 'pembimbing2']);
            })->paginate(10);

        return view('dosen.bimbingan.index', compact('mahasiswas'));
    }

    // 2. Tampilkan Logbook dari Mahasiswa Terpilih
    public function showLogbook($mahasiswa_id)
    {
        $dosen = $this->getDosen();
        $mahasiswa = Mahasiswa::with('user')->findOrFail($mahasiswa_id);
        
        $logbooks = Logbook::where('mahasiswa_id', $mahasiswa_id)
            ->where('dosen_id', $dosen->id)
            ->latest()
            ->get();

        return view('dosen.bimbingan.logbook', compact('mahasiswa', 'logbooks'));
    }

    public function updateLogbook(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);

        // 1. Tentukan aturan validasi dinamis berdasarkan tombol aksi yang di-klik
        if (in_array($request->status, ['disetujui', 'ditolak'])) {
            // Fase 1: Hanya validasi status booking
            $request->validate([
                'status' => 'required|in:disetujui,ditolak',
            ]);
        } else {
            // Fase 2: Validasi hasil review bab skripsi setelah pertemuan
            $request->validate([
                'catatan_dosen' => 'required|string|min:5',
                'status' => 'required|in:acc,revisi',
            ]);
        }

        // 2. Lakukan update data ke database
        $logbook->update([
            'catatan_dosen' => $request->catatan_dosen ?? $logbook->catatan_dosen,
            'status' => $request->status,
        ]);

        // 3. Berikan pesan alert sukses yang adaptif
        $message = 'Status bimbingan berhasil diperbarui!';
        if ($request->status === 'disetujui') { $message = 'Jadwal pertemuan bimbingan berhasil disetujui!'; }
        if ($request->status === 'ditolak') { $message = 'Jadwal pertemuan bimbingan telah ditolak.'; }
        if ($request->status === 'acc') { $message = 'Progres bab mahasiswa berhasil di-ACC!'; }
        if ($request->status === 'revisi') { $message = 'Catatan revisi berhasil dikirim ke mahasiswa.'; }

        return redirect()->back()->with('success', $message);
    }

    public function reviewLogbook(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);

        // Validasi kondisional berdasarkan fase tombol yang diklik
        if (in_array($request->status, ['disetujui', 'ditolak'])) {
            $request->validate([
                'status' => 'required|in:disetujui,ditolak',
            ]);
        } else {
            $request->validate([
                'catatan_dosen' => 'required|string|min:5',
                'status' => 'required|in:acc,revisi',
            ]);
        }

        // Update data logbook
        $logbook->update([
            'catatan_dosen' => $request->catatan_dosen ?? $logbook->catatan_dosen,
            'status' => $request->status,
        ]);

        // Membuat pesan notifikasi dinamis
        $message = match ($request->status) {
            'disetujui' => 'Jadwal pertemuan bimbingan berhasil disetujui!',
            'ditolak' => 'Jadwal pertemuan bimbingan telah ditolak.',
            'acc' => 'Progres bab mahasiswa berhasil di-ACC!',
            'revisi' => 'Catatan revisi berhasil dikirim ke mahasiswa.',
            default => 'Status bimbingan berhasil diperbarui!',
        };

        return redirect()->back()->with('success', $message);
    }
}