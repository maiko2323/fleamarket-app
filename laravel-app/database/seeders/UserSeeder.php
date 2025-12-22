<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'test_user1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'test_user2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password456'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'test_user3',
                'email' => 'user3@example.com',
                'password' => Hash::make('password789'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'test_user4',
                'email' => 'user4@example.com',
                'password' => Hash::make('password321'),
                'created_at' => now(),
            'updated_at' => now(),
            ],
            [
                'name' => 'test_user5',
                'email' => 'user5@example.com',
                'password' => Hash::make('password654'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
