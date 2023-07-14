<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'phone_number' => 43455546,
                'password' => Hash::make('admin123'),
                'role_id' => 1
            ],
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'phone_number' => 545466,
                'password' => Hash::make('user123'),
                'role_id' => 2
            ]
        ]);
    }
}
