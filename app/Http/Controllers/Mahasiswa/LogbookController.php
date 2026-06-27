<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Logbook;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    private function getMahasiswa()
    {
        return Mahasiswa::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $mahasiswa = $this->getMahasiswa();
        
        $logbooks = Logbook::with('dosen.user')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->paginate(10);

        return view('mahasiswa.logbook.index', compact('logbooks', 'mahasiswa'));
    }

    public function create()
    {
        $mahasiswa = $this->getMahasiswa();

        // Menggunakan relasi 'bimbinganPengajuan' milik model Dosen untuk memfilter
        $dosens = Dosen::with('user')
            ->whereHas('bimbinganPengajuan', function($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id)
                      ->whereIn('pembimbing.status', ['pembimbing1', 'pembimbing2']);
            })->get();

        if ($dosens->isEmpty()) {
            return redirect()->route('mahasiswa.logbook.index')
                ->with('error', 'Anda belum memiliki Dosen Pembimbing resmi untuk pengajuan judul Anda.');
        }

        return view('mahasiswa.logbook.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $mahasiswa = $this->getMahasiswa();

        // 1. Validasi Input
        $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'bab' => 'required|string',
            'kegiatan' => 'required|string',
            'tanggal_bimbingan' => 'required|date|after_or_equal:today', 
            'file_bab' => 'required|file|mimes:pdf,doc,docx|max:5120', 
        ]);

        // 2. Proses Custom Rename & Upload File Bab
        $filePath = null;
        if ($request->hasFile('file_bab')) {
            $file = $request->file('file_bab');
            
            // Mengambil ekstensi asli file (misal: pdf, docx)
            $extension = $file->getClientOriginalExtension();
            
            // Membersihkan string nama Bab agar tidak ada spasi (misal: "Bab 1" jadi "Bab-1")
            $slugBab = str_replace(' ', '-', $request->bab);
            
            // Menyusun nama file baru: NIM_Bab-1_202606271530.pdf
            $customFileName = $mahasiswa->nim . '_' . $slugBab . '_' . date('YmdHis') . '.' . $extension;
            
            // Simpan file ke folder 'storage/app/public/file_bab' dengan nama kustom
            $filePath = $file->storeAs('file_bab', $customFileName, 'public');
        }

        // 3. Simpan Data ke Database
        Logbook::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $request->dosen_id,
            'bab' => $request->bab,
            'kegiatan' => $request->kegiatan,
            'file_bab' => $filePath, 
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'status' => 'pending', 
        ]);

        return redirect()->route('mahasiswa.logbook.index')->with('success', 'Jadwal bimbingan berhasil di-booking dan file bab telah terkirim!');
    }
}