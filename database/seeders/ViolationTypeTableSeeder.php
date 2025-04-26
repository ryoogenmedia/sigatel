<?php

namespace Database\Seeders;

use App\Models\ViolationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViolationTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'terlambat',
                'description' => 'Terlambat dalam mengembalikan buku',
                'status' => true,
            ],
            [
                'name' => 'bolos',
                'description' => 'Bolos dari pembelajaran',
                'status' => true,
            ],
            [
                'name' => 'tidak sopan',
                'description' => 'Berkata kasar kepada guru',
                'status' => true,
            ],
            [
                'name' => 'merokok',
                'description' => 'Merokok di area sekolah',
                'status' => true,
            ],
        ];

        foreach($types as $type) {
           ViolationType::create($type);
        }
    }
}
