<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Exception;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $validator = Validator::make($rows->toArray(), [
            '*.email' => 'required|email|unique:users,email',
            '*.nim' => 'required|unique:mahasiswas,nim',
            '*.name' => 'required|string',
            '*.prodi_id' => 'required|exists:prodis,id',
        ], [
            '*.email.unique' => 'Proses gagal! Email ada yang duplikat di database.',
            '*.nim.unique' => 'Proses gagal! NIM ada yang duplikat di database.',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                $user = User::create([
                    'name'        => $row['name'],
                    'email'       => $row['email'],
                    'password'    => Hash::make($row['nim']),
                    'role'        => 'mahasiswa',
                    'is_verified' => true,
                ]);

                Mahasiswa::create([
                    'user_id'  => $user->id,
                    'prodi_id' => $row['prodi_id'],
                    'nim'      => $row['nim'],
                ]);
            }
        });
    }
}