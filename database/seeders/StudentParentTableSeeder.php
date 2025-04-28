<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Factory;

class StudentParentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        $studentIds = Student::pluck('id')->toArray();
        $startDate = Carbon::now()->subYears(5)->startOfYear();
        $endDate = Carbon::now();

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
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
                'guardian_status' => $faker->randomElement(['anak angkat', 'anak kandung']),
                'created_at'      => $currentDate,
                'updated_at'      => $currentDate,
            ]);

            $currentDate->addMonth();
        }
    }
}
