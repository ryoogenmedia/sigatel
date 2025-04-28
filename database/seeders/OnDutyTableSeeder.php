<?php

namespace Database\Seeders;

use App\Models\OnDuty;
use App\Models\SchoolSubject;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

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

        $startDate = Carbon::now()->subYears(5)->startOfYear();
        $endDate = Carbon::now();
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            for ($i = 0; $i < rand(1, 100); $i++) {
                $schoolSubject = $faker->randomElement($schoolSubjects);

                OnDuty::create([
                    'student_id'       => $faker->randomElement($studentIds),
                    'teacher_id'       => $schoolSubject->teacher_id,
                    'school_subject_id' => $schoolSubject->id,
                    'description'      => $faker->sentence,
                    'status'           => $faker->randomElement(config('const.duty_status')),
                    'violation_type'   => $faker->randomElement(config('const.violation_type')),
                    'schedule_time'    => $faker->dateTimeBetween('-1 week', '+1 week'),
                    'finish_time'      => $faker->dateTimeBetween('now', '+1 week'),
                    'created_at'       => $currentDate,
                    'updated_at'       => now(),
                ]);
            }
            $currentDate->addMonth();
        }
    }
}
