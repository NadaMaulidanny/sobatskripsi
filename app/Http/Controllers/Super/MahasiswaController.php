<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with(['user', 'prodi'])->latest()->paginate(10);
        return view('super.mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        $prodis = Prodi::orderBy('nama_prodi', 'asc')->get();
        return view('super.mahasiswa.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'prodi_id' => 'required|exists:prodis,id',
            'nim' => 'required|string|max:50|unique:mahasiswas,nim',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
                'is_verified' => false, 
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'prodi_id' => $request->prodi_id,
                'nim' => $request->nim,
            ]);
        });

        return redirect()->route('super_admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        $prodis = Prodi::orderBy('nama_prodi', 'asc')->get();
        return view('super.mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $user = $mahasiswa->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'prodi_id' => 'required|exists:prodis,id',
            'nim' => 'required|string|max:50|unique:mahasiswas,nim,' . $mahasiswa->id,
        ]);

        DB::transaction(function () use ($request, $user, $mahasiswa) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            $mahasiswa->update([
                'prodi_id' => $request->prodi_id,
                'nim' => $request->nim,
            ]);
        });

        return redirect()->route('super_admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        // Cascade delete otomatis membersihkan row di tabel mahasiswas
        $mahasiswa->user->delete(); 

        return redirect()->route('super_admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus!');
    }
}