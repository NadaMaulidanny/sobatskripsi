<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JadwalDosen;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        // Mengambil semua jadwal rutin milik dosen yang sedang login
        $jadwal = JadwalDosen::where('user_id', auth()->id())
            ->orderByRaw("FIELD(hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->get();

        return view('dosen.jadwal.index', compact('jadwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kuota_mahasiswa' => 'required|integer|min:1|max:10',
        ]);

        // Opsional: Cek jika hari dan jam yang sama sudah pernah diinput agar tidak duplikat
        $isDuplikat = JadwalDosen::where('user_id', auth()->id())
            ->where('hari', $request->hari)
            ->where('jam_mulai', $request->jam_mulai)
            ->exists();

        if ($isDuplikat) {
            return redirect()->back()->withErrors(['hari' => 'Anda sudah membuat jadwal di hari dan jam yang sama.']);
        }

        JadwalDosen::create([
            'user_id' => auth()->id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota_mahasiswa' => $request->kuota_mahasiswa,
        ]);

        return redirect()->back()->with('success', 'Jadwal rutin mingguan berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $jadwal = JadwalDosen::where('user_id', auth()->id())->findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal rutin berhasil dihapus.');
    }
}