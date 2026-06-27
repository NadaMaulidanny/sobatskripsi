<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::with(['user', 'prodi'])->latest()->paginate(10);
        return view('super.dosen.index', compact('dosens'));
    }

    public function create()
    {
        $prodis = Prodi::orderBy('nama_prodi', 'asc')->get();
        return view('super.dosen.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'prodi_id' => 'required|exists:prodis,id',
            'nidn' => 'required|string|max:50|unique:dosens,nidn',
            'kuota' => 'required|integer|min:0',
            'is_kaprodi' => 'nullable|boolean', // Input baru dari form
        ]);

        DB::transaction(function () use ($request) {
            // 1. Jika dia ditunjuk jadi Kaprodi, turunkan jabatan kaprodi lama di prodi yang sama
            if ($request->is_kaprodi) {
                $kaprodiLama = User::where('role', 'kaprodi')
                    ->whereHas('dosen', function ($query) use ($request) {
                        $query->where('prodi_id', $request->prodi_id);
                    })->update(['role' => 'dosen']);
            }

            // 2. Buat data User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->is_kaprodi ? 'kaprodi' : 'dosen', // Set role dinamis
                'is_verified' => true,
            ]);

            // 3. Buat data Dosen
            Dosen::create([
                'user_id' => $user->id,
                'prodi_id' => $request->prodi_id,
                'nidn' => $request->nidn,
                'kuota' => $request->kuota,
            ]);
        });

        return redirect()->route('super_admin.dosen.index')->with('success', 'Data Dosen berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $dosen = Dosen::with('user')->findOrFail($id);
        $prodis = Prodi::orderBy('nama_prodi', 'asc')->get();
        return view('super.dosen.edit', compact('dosen', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $user = $dosen->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'prodi_id' => 'required|exists:prodis,id',
            'nidn' => 'required|string|max:50|unique:dosens,nidn,' . $dosen->id,
            'kuota' => 'required|integer|min:0',
            'is_kaprodi' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $user, $dosen) {
            if ($request->is_kaprodi) {
                User::where('role', 'kaprodi')
                    ->whereHas('dosen', function ($query) use ($request) {
                        $query->where('prodi_id', $request->prodi_id);
                    })
                    ->where('id', '!=', $user->id)
                    ->update(['role' => 'dosen']);
            }

            // Update data User
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->is_kaprodi ? 'kaprodi' : 'dosen',
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // Update data Dosen
            $dosen->update([
                'prodi_id' => $request->prodi_id,
                'nidn' => $request->nidn,
                'kuota' => $request->kuota,
            ]);
        });

        return redirect()->route('super_admin.dosen.index')->with('success', 'Data Dosen berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->user->delete();

        return redirect()->route('super_admin.dosen.index')->with('success', 'Data Dosen berhasil dihapus!');
    }
}