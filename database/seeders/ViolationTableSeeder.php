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

        foreach (range(1, 20) as $i) {
            foreach ($studentIds as $studentId) {
                $createdAt = $faker->dateTimeBetween('-5 years', 'now');

                if ($i % 5 === 0) {
                    $createdAt = $faker->dateTimeThisYear(); // data tahun ini
                } elseif ($i % 7 === 0) {
                    $createdAt = $faker->dateTimeThisMonth(); // data bulan ini
                } elseif ($i % 9 === 0) {
                    $createdAt = $faker->dateTimeBetween('last Monday', 'now'); // data minggu ini
                } elseif ($i % 11 === 0) {
                    $createdAt = $faker->dateTimeBetween('today', 'now'); // data hari ini
                }

                Violation::create([
                    'student_id'     => $studentId,
                    'teacher_id'     => $faker->randomElement($teacherIds),
                    'violation_type' => $faker->randomElement($violationTypes),
                    'description'    => $faker->text(450),
                    'created_at'     => $createdAt,
                ]);
            }
        }
    }
}
