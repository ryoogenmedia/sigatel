<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $gradeIds = Grade::pluck('id')->toArray();

        foreach (range(1, 10) as $i) {
            $user = User::create([
                'username'          => $faker->name,
                'email'             => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password'          => Hash::make('student123'),
                'roles'             => 'student',
            ]);

            Student::create([
                'user_id'      => $user->id,
                'grade_id'     => $faker->randomElement($gradeIds),
                'name'         => $user->username,
                'phone_number' => $faker->phoneNumber,
                'sex'          => $faker->randomElement(['laki-laki', 'perempuan']),
                'address'      => $faker->address,
                'status'       => $faker->randomElement(['aktif', 'non aktif']),
            ]);
        }
    }
}
