<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Violation;
use App\Models\ViolationType;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ViolationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker      = Factory::create("id_ID");
        $studentIds = Student::pluck('id')->toArray();
        $teacherIds = Teacher::query()
            ->where('status', 'aktif')
            ->where('duty_status', true)
            ->pluck('id')->toArray();

        $violationTypes = ViolationType::pluck('name')->toArray();

        foreach(range(1,50) as $i){
            Violation::create([
                'student_id'     => $faker->randomElement($studentIds),
                'teacher_id'     => $faker->randomElement($teacherIds),
                'violation_type' => $faker->randomElement($violationTypes),
                'description'    => $faker->sentence(),
            ]);
        }
    }
}
