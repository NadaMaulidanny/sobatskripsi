<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\RedirectResponse;

class MahasiswaVerificationController extends Controller
{
    /**
     * Memverifikasi akun mahasiswa agar berstatus aktif
     */
    public function verify(Mahasiswa $mahasiswa): RedirectResponse
    {
        // 1. Ambil data user yang berelasi dengan mahasiswa ini
        $user = $mahasiswa->user;

        if (!$user) {
            return redirect()->back()->with('error', 'Data user tidak ditemukan.');
        }

        // 2. Ubah status is_verified menjadi true
        $user->update([
            'is_verified' => true
        ]);

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', "Akun mahasiswa {$user->name} berhasil diverifikasi!");
    }
}