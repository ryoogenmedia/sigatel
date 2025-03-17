<?php

namespace Database\Seeders;

use App\Models\SchoolSubject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class SchoolSubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $teacherIds = Teacher::pluck('id')->toArray();

        $subjects = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
            'IPS',
            'PPKN',
            'Seni Budaya',
            'Penjaskes',
            'Prakarya',
            'Agama'
        ];

        foreach ($subjects as $subject) {
            SchoolSubject::create([
                'teacher_id' => $faker->randomElement($teacherIds),
                'name'       => $subject,
                'code'       => strtoupper(substr($subject, 0, 3)) . rand(100, 999),
                'status'     => $faker->randomElement([false, true]),
            ]);
        }
    }
}
