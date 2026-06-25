<?php 

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    // 1. HALAMAN DAFTAR PRODI
    public function index()
    {
        $prodis = Prodi::latest()->paginate(10);
        return view('super.prodi.index', compact('prodis'));
    }

    // 2. HALAMAN FORM TAMBAH
    public function create()
    {
        return view('super.prodi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi',
        ]);

        Prodi::create($request->only('nama_prodi')); // Cukup simpan nama saja

        return redirect()->route('super_admin.prodi.index')->with('success', 'Program Studi berhasil ditambahkan!');
    }

    // 4. HALAMAN FORM EDIT
    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('super.prodi.edit', compact('prodi'));
    }

    // 5. PROSES UPDATE DATA
    public function update(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);

        $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi,' . $prodi->id,
        ]);

        $prodi->update($request->only('nama_prodi'));

        return redirect()->route('super_admin.prodi.index')->with('success', 'Program Studi berhasil diperbarui!');
    }

    // 6. PROSES HAPUS DATA
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('super_admin.prodi.index')->with('success', 'Program Studi berhasil dihapus!');
    }
}