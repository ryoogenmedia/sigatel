<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GradeImport implements ToCollection, WithHeadingRow {
    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            $teacher = Teacher::whereHas('user', function($query) use ($row) {
                $query->where('email', $row['email_wali_kelas']);
            })->first();

            $grade = Grade::create([
                'name'  => $row['nama_kelas'],
                'floor' => $row['lantai_gedung'],
            ]);

            if($teacher){
                $grade->update([
                    'teacher_id' => $teacher->id,
                ]);
            }
        }
    }
}
