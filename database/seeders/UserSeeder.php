<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. DATA AKUN ADMIN
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123123'),
            'role' => 'admin',
        ]);

        // 2. DATA AKUN DOSEN
        User::create([
            'name' => 'Dr. Ahmad Hidayat, M.T.',
            'email' => 'dosen@gmail.com',
            'password' => Hash::make('123123'),
            'role' => 'dosen',
        ]);

        User::create([
            'name' => 'Siti Aminah, S.Kom., M.Cs.',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('123123'),
            'role' => 'dosen',
        ]);

        // 3. DATA AKUN MAHASISWA
        User::create([
            'name' => 'Rizky Maulana',
            'email' => 'mahasiswa@gmail.com',
            'password' => Hash::make('123123'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'name' => 'Bambang Pamungkas',
            'email' => 'bambang@gmail.com',
            'password' => Hash::make('123123'),
            'role' => 'mahasiswa',
        ]);
    }
}