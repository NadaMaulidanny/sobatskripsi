<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Dosen;
use App\Models\BidangStudi;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search'          => $request->input('search'),
            'search_judul'    => $request->input('search_judul'),
            'status'          => $request->input('status'),
            'bidang_studi_id' => $request->input('bidang_studi_id'),
        ];

        $pengajuans = Pengajuan::with(['mahasiswa.user', 'bidangStudi'])
            ->filter($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString(); 

        $bidangStudis = BidangStudi::all();

        return view('kaprodi.pengajuan.index', compact('pengajuans', 'bidangStudis', 'filters'));
    }

    public function show(string $id): View
    {
        $pengajuan = Pengajuan::with(['mahasiswa.user', 'bidangStudi', 'pembimbingDosens.user'])
            ->findOrFail($id);

        $daftarDosen = Dosen::with(['user', 'bidangStudis'])
            ->withCount(['bimbinganPengajuan as total_bimbingan' => function ($query) {
                $query->whereIn('pembimbing.status', ['pembimbing1', 'pembimbing2']);
            }])
            ->get();

        return view('kaprodi.pengajuan.show', compact('pengajuan', 'daftarDosen'));
    }

    public function updateStatus(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_kaprodi' => 'nullable|string|max:500',
            'pembimbing_1_id' => 'nullable|exists:dosens,id',
            'pembimbing_2_id' => 'nullable|exists:dosens,id',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        
        $pengajuan->update([
            'status' => $request->status,
            'catatan_kaprodi' => $request->catatan_kaprodi,
        ]);

        if ($request->status === 'disetujui') {
            // Hapus plot pembimbing lama jika sebelumnya pernah di-set (agar kuota tidak bocor)
            $pengajuan->pembimbingDosens()->detach();

            // Ambil ID dosen yang baru di-input
            $pembimbing1 = $request->pembimbing_1_id;
            $pembimbing2 = $request->pembimbing_2_id;

            if ($request->filled('pembimbing_1_id')) {
                $pengajuan->pembimbingDosens()->attach($pembimbing1, [
                    'status' => 'pembimbing1',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // POTONG KUOTA DOSEN PEMBIMBING 1 DI DATABASE
                Dosen::where('id', $pembimbing1)->decrement('kuota');
            }

            if ($request->filled('pembimbing_2_id')) {
                $pengajuan->pembimbingDosens()->attach($pembimbing2, [
                    'status' => 'pembimbing2',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // POTONG KUOTA DOSEN PEMBIMBING 2 DI DATABASE
                Dosen::where('id', $pembimbing2)->decrement('kuota');
            }
        }

        $pesan = $request->status === 'disetujui' ? 'Pengajuan judul berhasil disetujui dan kuota dosen telah diperbarui!' : 'Pengajuan judul telah ditolak.';
        return redirect()->route('kaprodi.pengajuan.index')->with('success', $pesan);
    }
}