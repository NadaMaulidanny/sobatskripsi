<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Dosen;

class PengajuanController extends Controller
{
    public function index(): View
    {
        $pengajuans = Pengajuan::with(['mahasiswa.user', 'bidangStudi'])
            ->latest()
            ->get();

        return view('kaprodi.pengajuan.index', compact('pengajuans'));
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
            $pengajuan->pembimbingDosens()->detach();

            if ($request->filled('pembimbing_1_id')) {
                $pengajuan->pembimbingDosens()->attach($request->pembimbing_1_id, [
                    'status' => 'pembimbing1',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            if ($request->filled('pembimbing_2_id')) {
                $pengajuan->pembimbingDosens()->attach($request->pembimbing_2_id, [
                    'status' => 'pembimbing2',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $pesan = $request->status === 'disetujui' ? 'Pengajuan judul berhasil disetujui!' : 'Pengajuan judul telah ditolak.';
        return redirect()->route('kaprodi.pengajuan.index')->with('success', $pesan);
    }
}