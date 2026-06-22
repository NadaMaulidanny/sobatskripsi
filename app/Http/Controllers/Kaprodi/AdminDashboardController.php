<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'kaprodi')->orderby('id', 'desc')->get();
        $total_mahasiswa = User::where('role', 'mahasiswa')->count();
        $total_dosen = User::where('role', 'dosen')->count();

        return view('kaprodi.dashboard', compact('users', 'total_mahasiswa', 'total_dosen'));
    }
}
