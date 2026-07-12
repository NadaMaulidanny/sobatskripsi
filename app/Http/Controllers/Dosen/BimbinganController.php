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
    /**
     * Mengambil data model Dosen berdasarkan user yang sedang login
     */
    private function getDosen()
    {
        return Dosen::where('user_id', Auth::id())->firstOrFail();
    }

    /**
     * Menampilkan daftar mahasiswa yang dibimbing oleh dosen ini
     */
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

    /**
     * Menampilkan seluruh daftar logbook/riwayat bimbingan dari satu mahasiswa tertentu
     */
    public function showLogbook($mahasiswa_id)
    {
        // Pastikan mahasiswa yang diakses memang mahasiswa bimbingan dosen ini
        $dosen = $this->getDosen();
        $mahasiswa = Mahasiswa::with(['user'])->whereHas('pengajuans.pembimbingDosens', function($query) use ($dosen) {
            $query->where('dosen_id', $dosen->id);
        })->findOrFail($mahasiswa_id);

        // Ambil semua data logbook bimbingan milik mahasiswa tersebut
        $logbooks = Logbook::where('mahasiswa_id', $mahasiswa_id)
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        return view('dosen.bimbingan.logbook', compact('mahasiswa', 'logbooks'));
    }

    /**
     * Memproses aksi review logbook (Setuju, Tolak, ACC, Revisi)
     */
    public function reviewLogbook(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);

        // Kasus 1: Dosen menolak booking jadwal dari mahasiswa
        if ($request->status === 'ditolak') {
            $request->validate([
                'status' => 'required|in:ditolak',
                'alasan_dropdown' => 'required|string',
                'catatan_tambahan' => 'nullable|string',
            ]);

            // Menggabungkan pilihan dropdown dan isi komentar tambahan ke satu field database
            $gabungCatatan = "[" . $request->alasan_dropdown . "] " . ($request->catatan_tambahan ?? 'Tidak ada catatan tambahan.');

            $logbook->update([
                'catatan_dosen' => $gabungCatatan,
                'status' => 'ditolak',
            ]);
            
            $message = 'Jadwal pertemuan bimbingan berhasil ditolak dengan alasan sistem.';

        // Kasus 2: Dosen menyetujui booking jadwal dari mahasiswa
        } elseif ($request->status === 'disetujui') {
            $request->validate([ 
                'status' => 'required|in:disetujui' 
            ]);
            
            $logbook->update([ 
                'status' => 'disetujui' 
            ]);
            
            $message = 'Jadwal pertemuan bimbingan berhasil disetujui!';

        // Kasus 3: Pertemuan selesai, dosen memberikan status kelayakan Bab (ACC / Revisi)
        } else {
            $request->validate([
                'catatan_dosen' => 'required|string|min:5',
                'status' => 'required|in:acc,revisi',
            ]);

            $logbook->update([
                'catatan_dosen' => $request->catatan_dosen,
                'status' => $request->status,
            ]);
            
            $message = $request->status === 'acc' ? 'Progres bab berhasil di-ACC!' : 'Catatan evaluasi revisi berhasil dikirim.';
        }

        return redirect()->back()->with('success', $message);
    }
}