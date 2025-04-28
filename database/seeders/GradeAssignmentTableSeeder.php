<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\GradeAssignment;
use App\Models\SchoolSubject;
use App\Models\Teacher;
use Faker\Factory;
use Illuminate\Database\Seeder;

class GradeAssignmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create("id_ID");

        $teacherIds = Teacher::pluck('id')->toArray();
        $gradeIds = Grade::pluck('id')->toArray();
        $schoolSubjectIds = SchoolSubject::pluck('id')->toArray();

        // Data untuk 10 bulan terakhir
        foreach (range(1, 10) as $i) {
            GradeAssignment::create([
                'grade_id' => $faker->randomElement($gradeIds),
                'teacher_id' => $faker->randomElement($teacherIds),
                'school_subject_id' => $faker->randomElement($schoolSubjectIds),
                'longitude' => $faker->longitude($longitude = -119.41666412354, $latitude = -5.1499996185303),
                'latitude' => $faker->latitude($longitude = -119.41666412354, $latitude = -5.1499996185303),
                'reason_teacher' => $faker->text(100),
                'description' => $faker->text(200),
                'status' => $faker->boolean(),
                'schedule_time' => $faker->dateTime(),
                'finish_time' => $faker->dateTime(),
                'created_at' => now()->subMonths(rand(1, 10)),
                'updated_at' => now()->subMonths(rand(1, 10)),
            ]);
        }

        // Data untuk tahun ini
        foreach (range(1, 10) as $i) {
            GradeAssignment::create([
                'grade_id' => $faker->randomElement($gradeIds),
                'teacher_id' => $faker->randomElement($teacherIds),
                'school_subject_id' => $faker->randomElement($schoolSubjectIds),
                'longitude' => $faker->longitude($longitude = -119.41666412354, $latitude = -5.1499996185303),
                'latitude' => $faker->latitude($longitude = -119.41666412354, $latitude = -5.1499996185303),
                'reason_teacher' => $faker->text(100),
                'description' => $faker->text(200),
                'status' => $faker->boolean(),
                'schedule_time' => $faker->dateTime(),
                'finish_time' => $faker->dateTime(),
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now()->subDays(rand(1, 365)),
            ]);
        }

        // Data untuk minggu ini
        foreach (range(1, 10) as $i) {
            GradeAssignment::create([
                'grade_id' => $faker->randomElement($gradeIds),
                'teacher_id' => $faker->randomElement($teacherIds),
                'school_subject_id' => $faker->randomElement($schoolSubjectIds),
                'longitude' => $faker->longitude($longitude = -119.41666412354, $latitude = -5.1499996185303),
                'latitude' => $faker->latitude($longitude = -119.41666412354, $latitude = -5.1499996185303),
                'reason_teacher' => $faker->text(100),
                'description' => $faker->text(200),
                'status' => $faker->boolean(),
                'schedule_time' => $faker->dateTime(),
                'finish_time' => $faker->dateTime(),
                'created_at' => now()->subDays(rand(1, 7)),
                'updated_at' => now()->subDays(rand(1, 7)),
            ]);
        }

        // Data untuk hari ini
        foreach (range(1, 10) as $i) {
            GradeAssignment::create([
                'grade_id' => $faker->randomElement($gradeIds),
                'teacher_id' => $faker->randomElement($teacherIds),
                'school_subject_id' => $faker->randomElement($schoolSubjectIds),
                'longitude' => $faker->longitude($longitude = -119.41666412354, $latitude = -5.1499996185303),
                'latitude' => $faker->latitude($longitude = -119.41666412354, $latitude = -5.1499996185303),
                'reason_teacher' => $faker->text(100),
                'description' => $faker->text(200),
                'status' => $faker->boolean(),
                'schedule_time' => $faker->dateTime(),
                'finish_time' => $faker->dateTime(),
                'created_at' => now()->subHours(rand(1, 24)),
                'updated_at' => now()->subHours(rand(1, 24)),
            ]);
        }
    }
}
