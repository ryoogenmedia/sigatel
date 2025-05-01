<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TeacherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $startDate = Carbon::now()->subYears(5)->startOfYear();
        $endDate = Carbon::now();
        $currentDate = $startDate->copy();

        $teachers = [
            [
                'username'          => 'Bintang Sensei',
                'email'             => 'bintangsensei@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('teacher123'),
                'roles'             => 'teacher',
                'name'              => 'Bintang Sensei',
                'phone_number'      => '08234238498283',
                'address'           => 'BTN BPS BLK F8 NO 13, Sudiang, Biringkanayya, Makassar, Sulawesi Selatan',
                'sex'               => 'laki-laki',
                'status'            => 'aktif',
            ],
        ];

        foreach($teachers as $teacher) {
            $user = User::create([
                'username'          => $teacher['username'],
                'email'             => $teacher['email'],
                'email_verified_at' => $teacher['email_verified_at'],
                'password'          => $teacher['password'],
                'roles'             => $teacher['roles'],
            ]);

            Teacher::create([
                'user_id'       => $user->id,
                'name'          => $teacher['name'],
                'phone_number'  => $teacher['phone_number'],
                'address'       => $teacher['address'],
                'sex'           => $teacher['sex'],
                'status'        => $teacher['status'],
            ]);
        }

        while ($currentDate <= $endDate) {
            $user = User::create([
                'username'          => $faker->name,
                'email'             => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password'          => Hash::make('teacher123'),
                'roles'             => 'teacher',
            ]);

            Teacher::create([
                'user_id'       => $user->id,
                'name'          => $user->username,
                'phone_number'  => $faker->phoneNumber,
                'address'       => $faker->address,
                'sex'           => $faker->randomElement(['laki-laki', 'perempuan']),
                'status'        => $faker->randomElement(config('const.teacher_status')),
                'created_at'    => $currentDate,
                'updated_at'    => now(),
            ]);

            $currentDate->addMonth();
        }
    }
}
