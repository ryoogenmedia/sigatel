<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentParentImport implements ToCollection, WithHeadingRow {
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

            $student = Student::where('nis', $row['nis_siswa'])->first();

            StudentParent::create([
                'user_id'           => $user->id,
                'student_id'        => $student->id,
                'name'              => $row['nama'],
                'phone_number'      => $row['nomor_ponsel'],
                'guardian_status'   => $row['status_wali'] ?? '',
            ]);
        }
    }
}
