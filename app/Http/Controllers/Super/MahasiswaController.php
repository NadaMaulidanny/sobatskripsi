<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MahasiswaExport;
use Exception;

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
        $mahasiswa->user->delete(); 

        return redirect()->route('super_admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus!');
    }

    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file_excel'));
            
            return redirect()->back()->with('success', 'Semua data mahasiswa berhasil diimport!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function exportMahasiswaExcel(Request $request) 
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $fileName = 'data_mahasiswa_' . ($startDate && $endDate ? "${startDate}_to_${endDate}" : date('Y-m-d'));
        
        return Excel::download(new MahasiswaExport($startDate, $endDate), $fileName . '.xlsx');
    }

    public function exportMahasiswaPdf(Request $request) 
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Mahasiswa::with(['user', 'prodi']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                $startDate . ' 00:00:00', 
                $endDate . ' 23:59:59'
            ]);
        }

        $mahasiswas = $query->get();

        // 1. Set nama file dinamis agar tidak 'Untitled'
        $fileName = 'Laporan_Data_Mahasiswa_';
        if ($startDate && $endDate) {
            $fileName .= $startDate . '_to_' . $endDate;
        } else {
            $fileName .= date('Y-m-d');
        }
        $fileName .= '.pdf';

        // 2. Render HTML view ke dalam string text
        $html = view('super.mahasiswa.export_pdf', compact('mahasiswas', 'startDate', 'endDate'))->render();

        // 3. Inisialisasi Native Dompdf Vendor (Bypass error class not found)
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        
        // Set ukuran kertas A4 Portrait
        $dompdf->setPaper('A4', 'portrait');
        
        // Proses rendering HTML ke file PDF
        $dompdf->render();

        // 4. Stream & download file langsung ke browser admin
        return $dompdf->stream($fileName, ["Attachment" => true]);
    }
}