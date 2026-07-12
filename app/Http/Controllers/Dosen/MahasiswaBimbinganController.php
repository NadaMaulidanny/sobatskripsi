<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengajuan;
use App\Models\Dosen;

class MahasiswaBimbinganController extends Controller
{
    public function index()
    {
        $dosen = auth()->user()->dosen;

        $mahasiswaBimbingan = $dosen->pengajuans()
            ->where('status', 'disetujui')
            ->with(['mahasiswa.user', 'bidangStudi'])
            ->get()
            ->pluck('mahasiswa')
            ->unique('id');

        return view('dosen.mahasiswa_bimbingan.index', compact('mahasiswaBimbingan'));
    }
}
