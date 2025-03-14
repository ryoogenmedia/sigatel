<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        File::deleteDirectory(public_path('storage/avatar'));
        File::deleteDirectory(public_path('storage/avatars'));

        Storage::deleteDirectory('public/avatar');
        Storage::deleteDirectory('public/avatars');

        $this->call([
            UserTableSeeder::class,
            TeacherTableSeeder::class,
            GradeTableSeeder::class,
            SchoolSubjectTableSeeder::class,
            StudentTableSeeder::class,
            StudentParentTableSeeder::class,
            OnDutyTableSeeder::class,
        ]);
    }
}
