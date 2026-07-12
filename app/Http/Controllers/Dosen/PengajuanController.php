<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengajuan;
use App\Models\Mahasiswa;

class PengajuanController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;

        if (!$dosen) {
            return view('dosen.pengajuan.index', [
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

        return view('dosen.pengajuan.index', compact('pengajuans'));
    }

    public function show($id)
    {
        
        $pengajuan = Pengajuan::with(['mahasiswa.user', 'bidangStudi', 'pembimbingDosens'])
                              ->findOrFail($id);

        return view('dosen.pengajuan.show', compact('pengajuan'));
    }

    public function storeReview(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $dosen = Auth::user()->dosen;

        // Keamanan: Validasi kesesuaian bidang studi dosen dengan pengajuan
        $bidangDosen = $dosen->bidangStudis()->pluck('bidang_studi_id')->toArray();
        if (!in_array($pengajuan->bidang_studi_id, $bidangDosen)) {
            abort(403, 'Anda tidak memiliki hak akses untuk mereview pengajuan ini.');
        }

        // Validasi input
        $request->validate([
            'catatan_input' => 'required|string|min:10',
        ], [
            'catatan_input.required' => 'Catatan tidak boleh kosong.',
            'catatan_input.min' => 'Catatan bimbingan minimal berisi 10 karakter.',
        ]);

        // 1. Ambil data catatan lama dari kolom 'catatan_dosen'
        $allCatatan = $pengajuan->catatan_dosen ?? [];

        // 2. Buat struktur data review baru
        $newReview = [
            'dosen_id'   => $dosen->id,
            'nama_dosen' => Auth::user()->name, 
            'catatan'    => $request->catatan_input,
            'tanggal'    => now()->toDateTimeString(),
        ];

        // 3. Masukkan data baru ke baris paling bawah array
        $allCatatan[] = $newReview;

        // 4. Update kolom catatan_dosen di database
        $pengajuan->update([
            'catatan_dosen' => $allCatatan
        ]);

        return redirect()->back()->with('success', 'Catatan Anda berhasil ditambahkan!');
    }
}
