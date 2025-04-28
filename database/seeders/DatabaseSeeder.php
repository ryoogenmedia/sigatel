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
        File::deleteDirectory(public_path('storage/logo-school'));
        File::deleteDirectory(public_path('storage/documentation-student'));
        File::deleteDirectory(public_path('storage/documentation-activity'));
        File::deleteDirectory(public_path('storage/dokumentasi-tugas-kelas'));
        File::deleteDirectory(public_path('storage/documentation-kegiatan'));

        Storage::deleteDirectory('public/avatar');
        Storage::deleteDirectory('public/avatars');
        Storage::deleteDirectory('public/logo-school');
        Storage::deleteDirectory('public/documentation-student');
        Storage::deleteDirectory('public/dokumentasi-tugas-kelas');
        Storage::deleteDirectory('public/documentation-kegiatan');

        $this->call([
            UserTableSeeder::class,
            TeacherTableSeeder::class,
            GradeTableSeeder::class,
            SchoolSubjectTableSeeder::class,
            StudentTableSeeder::class,
            StudentParentTableSeeder::class,
            OnDutyTableSeeder::class,
            ViolationTypeTableSeeder::class,
            TeacherDutyActiveTableSeeder::class,
            ViolationTableSeeder::class,
            GradeAssignmentTableSeeder::class,
        ]);
    }
}
