<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\JadwalDosen;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogbookController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        $logbooks = Logbook::with('dosen.user') // Eager load data dosen agar performa enteng
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('tanggal_bimbingan', 'desc')
            ->paginate(10);

        return view('mahasiswa.logbook.index', compact('logbooks'));
    }
    
    public function create()
    {
        $mahasiswa = Auth::user()->mahasiswa; 

        $pengajuanAktif = $mahasiswa->pengajuans()
            ->with(['pembimbingDosens.user'])
            ->where('status', 'disetujui') 
            ->first();
        
        if (!$pengajuanAktif) {
            return redirect()->route('mahasiswa.logbook.index')
                ->with('error', 'Anda belum memiliki pengajuan pembimbing skripsi yang disetujui.');
        }

        $pembimbing1 = $pengajuanAktif->pembimbingDosens
            ->first(function($dosen) {
                return $dosen->pivot->status === 'pembimbing1';
            });

        $pembimbing2 = $pengajuanAktif->pembimbingDosens
            ->first(function($dosen) {
                return $dosen->pivot->status === 'pembimbing2';
            });

        return view('mahasiswa.logbook.create', compact('mahasiswa', 'pembimbing1', 'pembimbing2'));
    }

    public function getHariDosen($dosen_id)
    {
        $dosen = Dosen::find($dosen_id);
        
        if (!$dosen) {
            return response()->json([]);
        }

        $hariTersedia = JadwalDosen::where('user_id', $dosen->user_id)
                            ->pluck('hari')
                            ->toArray();
        
        return response()->json($hariTersedia);
    }

    /**
     * Menyimpan Data Pengajuan Logbook Baru ke Database
     */
    public function store(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        // 1. Validasi Form Dasar + pastikan dosen target dipilih
        $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'tanggal_bimbingan' => 'required|date|after_or_equal:today',
            'bab' => 'required|string',
            'kegiatan' => 'required|string|min:10',
            'file_bab' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        // 2. Ambil hari ketersediaan dosen target dari database
        $dosenTarget = \App\Models\Dosen::findOrFail($request->dosen_id);
        $hariTersedia = JadwalDosen::where('user_id', $dosenTarget->user_id)->pluck('hari')->toArray();
        
        // 3. Deteksi nama hari pilihan mahasiswa ('Monday', 'Tuesday', dll)
        $hariInput = Carbon::parse($request->tanggal_bimbingan)->format('l');

        // 4. Validasi Keamanan Backend: Cek kecocokan hari operasional dosen target
        if (count($hariTersedia) > 0 && !in_array($hariInput, $hariTersedia)) {
            return redirect()->back()
                ->withErrors(['tanggal_bimbingan' => 'Gagal! Tanggal tidak sesuai dengan jadwal operasional dosen pembimbing yang Anda pilih.'])
                ->withInput();
        }

        // 5. Proses Upload Berkas Pendukung (jika ada)
        $pathFile = null;
        if ($request->hasFile('file_bab')) {
            $pathFile = $request->file('file_bab')->store('file_bimbingan', 'public');
        }

        // 6. Simpan permanen data logbook baru
        Logbook::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $request->dosen_id, // Pastikan tabel logbooks kamu punya kolom dosen_id ya!
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'bab' => $request->bab,
            'kegiatan' => $request->kegiatan,
            'file_bab' => $pathFile,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.logbook.index')->with('success', 'Request jadwal bimbingan baru berhasil dikirim ke dosen target!');
    }
}