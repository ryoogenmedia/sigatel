<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentParentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $studentIds = Student::pluck('id')->toArray();

        foreach (range(1, 10) as $i) {
            $user = User::create([
                'username'          => $faker->name,
                'email'             => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password'          => Hash::make('parent123'),
                'roles'             => 'parent',
            ]);

            StudentParent::create([
                'user_id'         => $user->id,
                'student_id'      => $faker->randomElement($studentIds),
                'name'            => $user->username,
                'phone_number'    => $faker->phoneNumber,
                'guardian_status' => $faker->randomElement(['orangtua-kandung', 'orangtua-wali']),
            ]);
        }
    }
}
