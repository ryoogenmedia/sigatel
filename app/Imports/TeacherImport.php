<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeacherImport implements ToCollection, WithHeadingRow {
    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            $user = User::create([
                'username'          => $row['nama'],
                'roles'             => 'teacher',
                'email'             => $row['email'],
                'password'          => bcrypt($row['password']),
                'email_verified_at' => now(),
            ]);

            Teacher::create([
                'user_id'       => $user->id,
                'name'          => $row['nama'],
                'phone_number'  => $row['nomor_ponsel'],
                'address'       => $row['alamat'],
                'sex'           => $row['jenis_kelamin'],
            ]);
        }
    }
}
