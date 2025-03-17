<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $teacherIds = Teacher::pluck('id')->toArray();

        foreach (range(1, 10) as $i) {
            Grade::create([
                'teacher_id' => $faker->randomElement($teacherIds),
                'name'       => $faker->randomElement(['I', 'II', 'III', 'IV', 'V', 'VI']) . "-" . $faker->numberBetween(00, 99),
                'floor'      => $faker->numberBetween(1, 5),
            ]);
        }
    }
}
