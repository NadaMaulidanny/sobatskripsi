<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\BidangStudi;
use App\Models\Pengajuan; 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Dosen;

class PengajuanController extends Controller
{
    /**
     * Menampilkan Halaman Riwayat Pengajuan Judul Mahasiswa
     */
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $pengajuans = Pengajuan::with('bidangStudi')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.pengajuan.index', compact('pengajuans'));
    }

    public function create(): View
    {
        $mahasiswaId = auth()->user()->mahasiswa->id;

        // Cek apakah ada pengajuan aktif yang berstatus 'menunggu' atau 'disetujui'
        $punyaPengajuanAktif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        // Jika punya pengajuan aktif, tendang kembali ke index dengan pesan error
        if ($punyaPengajuanAktif) {
            return redirect()->route('mahasiswa.pengajuan.index')
                ->with('error', 'Anda tidak dapat membuat pengajuan baru karena masih memiliki pengajuan aktif atau sudah disetujui.');
        }

        $bidangStudis = BidangStudi::all();
        
        $daftarDosen = Dosen::with(['user', 'bidangStudis'])
            ->withCount(['bimbinganPengajuan as total_bimbingan' => function ($query) {
                $query->whereIn('pembimbing.status', ['pembimbing1', 'pembimbing2']);
            }])
            ->get();

        $dosens = $daftarDosen->map(function ($dosen) {
            return [
                'id' => $dosen->id,
                'nama' => $dosen->user->name ?? 'Dosen Tanpa Nama',
                'bidang_ids' => $dosen->bidangStudis->pluck('id')->toArray()
            ];
        });

        return view('mahasiswa.pengajuan.create', compact('bidangStudis', 'dosens', 'daftarDosen'));
    }

    public function store(Request $request)
    {
        $mahasiswaId = auth()->user()->mahasiswa->id;

        // VALIDASI BE: Pastikan sekali lagi di database benar-benar tidak ada yang 'menunggu'/'disetujui'
        $punyaPengajuanAktif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($punyaPengajuanAktif) {
            return redirect()->route('mahasiswa.pengajuan.index')
                ->with('error', 'Proses gagal. Anda masih memiliki pengajuan aktif yang sedang berjalan.');
        }

        // Ubah validasi name input mengikuti struktur custom dropdown baru yang kita buat tadi (pembimbing_1_id)
        $request->validate([
            'judul' => 'required|string|max:255',
            'bidang_studi_id' => 'required|exists:bidang_studis,id',
            'deskripsi' => 'required|string',
            'pembimbing_1_id' => 'nullable|exists:dosens,id',
            'pembimbing_2_id' => 'nullable|exists:dosens,id',
        ]);

        $pengajuan = Pengajuan::create([
            'mahasiswa_id' => $mahasiswaId,
            'judul' => $request->judul,
            'bidang_studi_id' => $request->bidang_studi_id,
            'deskripsi' => $request->deskripsi,
            'status' => 'menunggu',
        ]);

        // Simpan relasi bimbingan mengikuti name input hidden custom dropdown baru
        if ($request->filled('pembimbing_1_id')) {
            $pengajuan->pembimbingDosens()->attach($request->pembimbing_1_id, [
                'status' => 'request1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($request->filled('pembimbing_2_id')) {
            $pengajuan->pembimbingDosens()->attach($request->pembimbing_2_id, [
                'status' => 'request2',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('mahasiswa.pengajuan.index')
            ->with('success', 'Pengajuan judul dan request dosen pembimbing berhasil dikirim!');
    }

    public function show(string $id): \Illuminate\View\View
    {
        $pengajuan = Pengajuan::with(['bidangStudi', 'pembimbingDosens.user'])
            ->findOrFail($id);

        if ($pengajuan->mahasiswa_id !== auth()->user()->mahasiswa->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat pengajuan ini.');
        }

        return view('mahasiswa.pengajuan.show', compact('pengajuan'));
    }
}