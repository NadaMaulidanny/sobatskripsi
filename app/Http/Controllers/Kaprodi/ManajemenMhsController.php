<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ManajemenMhsController extends Controller
{
    public function daftar(Request $request): View
    {
        $user = $request->user();

        $mahasiswas = Mahasiswa::with('user')
            ->where('prodi_id', $user->dosen->prodi_id)
            ->get();

        return view('kaprodi.manajemenMahasiswa.daftar', compact('mahasiswas'));
    }

    public function create(): View
    {
        return view('kaprodi.manajemenMahasiswa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'password' => 'required|string|min:8',
        ]);

        $prodiIdKaprodi = auth()->user()->dosen->prodi_id;

        DB::transaction(function () use ($request, $prodiIdKaprodi) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
                'is_verified' => true, 
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'prodi_id' => $prodiIdKaprodi,
                'nim' => $request->nim,
            ]);
        });

        return redirect()->route('kaprodi.daftar-mhs')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function edit(string $id): View
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        // Proteksi jika kaprodi nakal ganti ID di URL ke prodi lain
        if ($mahasiswa->prodi_id !== auth()->user()->dosen->prodi_id) {
            abort(403, 'Anda tidak diizinkan mengakses data mahasiswa prodi lain.');
        }

        return view('kaprodi.manajemenMahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        if ($mahasiswa->prodi_id !== auth()->user()->dosen->prodi_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $mahasiswa->user_id,
            'nim' => 'required|string|max:20|unique:mahasiswas,nim,' . $mahasiswa->id,
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function () use ($request, $mahasiswa) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $mahasiswa->user->update($userData);
            $mahasiswa->update(['nim' => $request->nim]);
        });

        return redirect()->route('kaprodi.daftar-mhs')->with('success', 'Data mahasiswa berhasil diubah!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        if ($mahasiswa->prodi_id !== auth()->user()->dosen->prodi_id) {
            abort(403);
        }

        DB::transaction(function () use ($mahasiswa) {
            $user = $mahasiswa->user;
            $mahasiswa->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('kaprodi.daftar-mhs')->with('success', 'Mahasiswa berhasil dihapus!');
    }
}