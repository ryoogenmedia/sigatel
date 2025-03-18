<?php

namespace Database\Seeders;

use App\Models\OnDuty;
use App\Models\SchoolSubject;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OnDutyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil ID dari tabel terkait
        $studentIds = Student::pluck('id')->toArray();
        $schoolSubjects = SchoolSubject::with('teacher')->get();

        foreach (range(1, 20) as $i) {
            // Ambil satu mata pelajaran secara acak
            $schoolSubject = $faker->randomElement($schoolSubjects);

            OnDuty::create([
                'student_id'       => $faker->randomElement($studentIds),
                'teacher_id'       => $schoolSubject->teacher_id,
                'school_subject_id' => $schoolSubject->id,
                'longitude'        => $faker->randomFloat(6, 119.395, 119.548),
                'latitude'         => $faker->randomFloat(6, -5.176, -5.065),
                'description'      => $faker->sentence,
                'status'           => $faker->randomElement(config('const.duty_status')),
                'violation_type'   => $faker->randomElement(config('const.violation_type')),
                'schedule_time'    => $faker->dateTimeBetween('-1 week', '+1 week'),
                'finish_time'      => $faker->dateTimeBetween('now', '+1 week'),
            ]);
        }
    }
}
