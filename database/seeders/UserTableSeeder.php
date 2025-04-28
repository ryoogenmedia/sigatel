<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'muhammad bintang ramli',
                'email' => 'muhbintang650@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('bintang123'),
                'roles' => 'admin',
            ],
            [
                'username' => 'fery fadul rahman',
                'email' => 'feryfadulrahman@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('fery123'),
                'roles' => 'admin',
            ],
            [
                'username' => 'rinary admin',
                'email' => 'rinary66@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('rinary123321'),
                'roles' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
