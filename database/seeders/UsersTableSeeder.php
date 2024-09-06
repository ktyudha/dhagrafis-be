<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            $writer = User::create([
                'name' => "Writer {$i}",
                'username' => "writer{$i}",
                'email' => "writer{$i}@gmail.com",
                'password' => bcrypt('password')
            ]);

            $writer->assignRole('writer');
        }

        // for ($i = 1; $i <= 2; $i++) {
        //     $user = User::create([
        //         'name' => "User {$i}",
        //         'username' => "user{$i}",
        //         'email' => "user{$i}@gmail.com",
        //         'password' => bcrypt('password')
        //     ]);

        //     $user->assignRole('user');
        // }

    }
}
