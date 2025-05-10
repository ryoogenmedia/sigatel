<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToCollection, WithHeadingRow {
    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            $user = User::create([
                'username'          => $row['nama_siswa'],
                'roles'             => 'student',
                'email'             => $row['email'],
                'password'          => bcrypt($row['password']),
                'email_verified_at' => now(),
            ]);

            $grade = Grade::where('name', $row['nama_kelas'])->first();

            Student::create([
                'user_id'       => $user->id,
                'grade_id'      => $grade->id,
                'name'          => $row['nama_siswa'],
                'phone_number'  => $row['nomor_ponsel'],
                'sex'           => $row['jenis_kelamin'],
                'address'       => $row['alamat'],
                'nis'           => $row['nis'],
            ]);
        }
    }
}
