<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManajemenDosenController extends Controller
{
    public function index(Request $request): View
{
    $prodiId = auth()->user()->dosen->prodi_id;

    $dosens = Dosen::with(['user', 'bidangStudis'])->where('prodi_id', $prodiId)->get();

    return view('kaprodi.dosen.index', compact('dosens'));
}
}