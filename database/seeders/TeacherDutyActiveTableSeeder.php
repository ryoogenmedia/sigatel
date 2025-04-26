<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherDutyActiveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create("id_ID");

        $teacherIds = Teacher::query()
            ->where('status', 'aktif')
            ->pluck('id')->toArray();

        foreach(range(1,10) as $i){
            $teacher = Teacher::find($faker->randomElement($teacherIds));
            $teacher->update([
                'duty_status' => true,
            ]);
        }
    }
}
