<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\BidangStudi;
use App\Models\Pengajuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PengajuanSkripsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Data Prodi (Program Studi)
        $prodiTI = Prodi::create(['nama_prodi' => 'Teknik Informatika']);
        $prodiSI = Prodi::create(['nama_prodi' => 'Sistem Informasi']);

        // 2. Buat Data Bidang Studi / Konsentrasi
        $ai = BidangStudi::create(['nama' => 'Artificial Intelligence', 'prodi_id' => $prodiTI->id]);
        $networking = BidangStudi::create(['nama' => 'Computer Network & Security', 'prodi_id' => $prodiTI->id]);
        $bi = BidangStudi::create(['nama' => 'Business Intelligence', 'prodi_id' => $prodiSI->id]);


        // ==========================================
        // 3. SEEDING AKUN SUPER ADMIN & KAPRODI
        // ==========================================
        
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'is_verified' => true,
        ]);

        // Kaprodi Teknik Informatika (User + Profil Dosen)
        $userKaprodi = User::create([
            'name' => 'Dr. Budi Santoso, M.T.',
            'email' => 'budi.kaprodi@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'kaprodi',
            'is_verified' => true,
        ]);
        
        $dosenKaprodi = Dosen::create([
            'user_id' => $userKaprodi->id,
            'prodi_id' => $prodiTI->id,
            'nidn' => '0412345601',
        ]);
        // Pasangkan Kaprodi ke keahlian AI
        $dosenKaprodi->bidangStudis()->attach($ai->id);


        // ==========================================
        // 4. SEEDING AKUN DOSEN BIASA
        // ==========================================
        
        // Dosen 1 (Spesialis Networking)
        $userDosen1 = User::create([
            'name' => 'Ahmad Fauzi, M.Kom.',
            'email' => 'ahmad.fauzi@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
            'is_verified' => true,
        ]);
        $dosen1 = Dosen::create([
            'user_id' => $userDosen1->id,
            'prodi_id' => $prodiTI->id,
            'nidn' => '0412345602',
        ]);
        $dosen1->bidangStudis()->attach($networking->id);

        // Dosen 2 (Spesialis AI)
        $userDosen2 = User::create([
            'name' => 'Siti Aminah, Ph.D.',
            'email' => 'siti.aminah@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
            'is_verified' => true,
        ]);
        $dosen2 = Dosen::create([
            'user_id' => $userDosen2->id,
            'prodi_id' => $prodiTI->id,
            'nidn' => '0412345603',
        ]);
        $dosen2->bidangStudis()->attach($ai->id);


        // ==========================================
        // 5. SEEDING AKUN MAHASISWA & PENGAJUAN JUDUL
        // ==========================================
        
        // Mahasiswa 1
        $userMhs1 = User::create([
            'name' => 'Rian Hidayat',
            'email' => 'rian.mhs@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'is_verified' => true,
        ]);
        $mhs1 = Mahasiswa::create([
            'user_id' => $userMhs1->id,
            'prodi_id' => $prodiTI->id,
            'nim' => '2023001001',
        ]);

        // Pengajuan Judul Pertama (Status: Menunggu)
        Pengajuan::create([
            'mahasiswa_id' => $mhs1->id,
            'judul' => 'Implementasi Deep Learning untuk Deteksi Penyakit Daun Tomat',
            'deskripsi' => 'Penelitian ini mendeteksi penyakit tanaman tomat menggunakan algoritma CNN berbasis web.',
            'bidang_studi_id' => $ai->id,
            'status' => 'menunggu',
        ]);

        // ------------------------------------------

        // Mahasiswa 2 (Contoh Judul yang sudah DISETUJUI & dapat PEMBIMBING)
        $userMhs2 = User::create([
            'name' => 'Dinda Lestari',
            'email' => 'dinda.mhs@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'is_verified' => true,
        ]);
        $mhs2 = Mahasiswa::create([
            'user_id' => $userMhs2->id,
            'prodi_id' => $prodiTI->id,
            'nim' => '2023001002',
        ]);

        $pengajuanDisetujui = Pengajuan::create([
            'mahasiswa_id' => $mhs2->id,
            'judul' => 'Analisis Keamanan Jaringan IoT Menggunakan Metode Intrusion Detection System',
            'deskripsi' => 'Menganalisis serangan DDoS pada perangkat smart home menggunakan sistem IDS.',
            'bidang_studi_id' => $networking->id,
            'status' => 'disetujui',
            'catatan_kaprodi' => 'Judul menarik dan relevan. Lanjutkan ke bimbingan.',
        ]);

        // Menghubungkan Dosen Pembimbing ke Pengajuan yang Disetujui (Tabel Pivot Pembimbing)
        // Dosen 1 sebagai Pembimbing 1, Dosen Kaprodi sebagai Pembimbing 2
        $pengajuanDisetujui->pembimbingDosens()->attach([
            $dosen1->id => ['status' => 'pembimbing1'],
            $dosenKaprodi->id => ['status' => 'pembimbing2']
        ]);
    }
}