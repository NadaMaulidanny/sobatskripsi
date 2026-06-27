<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\BidangStudi;
use App\Models\Prodi;
use Illuminate\Http\Request;

class BidangStudiController extends Controller
{
    public function index()
    {
        $bidangStudis = BidangStudi::with('prodi')->latest()->paginate(10);

        return view('super.bidang-studi.index', compact('bidangStudis'));
    }

    public function create()
    {
        // Ambil semua prodi untuk pilihan dropdown di form
        $prodis = Prodi::orderBy('nama_prodi', 'asc')->get();
        return view('super.bidang-studi.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodis,id',
        ]);

        BidangStudi::create($request->only('nama', 'prodi_id'));

        return redirect()->route('super_admin.bidang-studi.index')->with('success', 'Bidang Studi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $bidangStudi = BidangStudi::findOrFail($id);
        $prodis = Prodi::orderBy('nama_prodi', 'asc')->get();
        
        return view('super.bidang-studi.edit', compact('bidangStudi', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $bidangStudi = BidangStudi::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodis,id',
        ]);

        $bidangStudi->update($request->only('nama', 'prodi_id'));

        return redirect()->route('super_admin.bidang-studi.index')->with('success', 'Bidang Studi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $bidangStudi = BidangStudi::findOrFail($id);
        $bidangStudi->delete();

        return redirect()->route('super_admin.bidang-studi.index')->with('success', 'Bidang Studi berhasil dihapus!');
    }
}