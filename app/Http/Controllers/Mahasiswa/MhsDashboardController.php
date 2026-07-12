<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class MhsDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $mahasiswa = $user->mahasiswa;

        $pengajuans = collect();
        $pengajuanTerakhir = null;

        if ($mahasiswa) {
            $pengajuans = Pengajuan::where('mahasiswa_id', $mahasiswa->id)->with('pembimbingDosens')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $pengajuanTerakhir = $pengajuans->first();
        }

        return view('mahasiswa.dashboard', compact('user', 'mahasiswa', 'pengajuans', 'pengajuanTerakhir'));
    }
}