<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Factory;

class StudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        $gradeIds = Grade::pluck('id')->toArray();

        $startDate = Carbon::now()->subYears(5)->startOfYear();
        $endDate = Carbon::now();
        $currentDate = $startDate->copy();

        $students = [
            [
                'username'          => 'fardiansyah',
                'email'             => 'fardiansyah@gmail.com',
                'email_verified_at' => now(),
                'password'          => bcrypt('fardiansyah123'),
                'roles'             => 'student',
                'name'              => 'Muh Fardiansyah',
                'phone_number'      => '086328746873',
                'sex'               => 'laki-laki',
                'address'           => 'Jl Perintis Kemerdekaan Km 18',
                'nis'               => '999-888',
                'status'            => 'aktif',
            ]
        ];

        foreach($students as $student){
            $dtUser = User::create([
                'username'          => $student['username'],
                'email'             => $student['email'],
                'email_verified_at' => $student['email_verified_at'],
                'password'          => $student['password'],
                'roles'             => $student['roles'],
            ]);

            Student::create([
                'user_id'      => $dtUser->id,
                'grade_id'     => $faker->randomElement($gradeIds),
                'name'         => $student['name'],
                'phone_number' => $student['phone_number'],
                'sex'          => $student['sex'],
                'address'      => $student['address'],
                'status'       => $student['status'],
                'nis'          => $student['nis'],
            ]);
        }

        while ($currentDate <= $endDate) {
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
                'created_at'   => $currentDate,
                'nis'          => $faker->unique()->numberBetween(00000000, 99999999),
                'updated_at'   => now(),
            ]);

            $currentDate->addMonth();
        }
    }
}
