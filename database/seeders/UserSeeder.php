<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'rushinsandeepana77@gmail.com',
                'email' => 'rushinsandeepana77@gmail.com',
                'role' => 'user',
                'password' => Hash::make('12345678'), // Hashed password
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'pharmacy@gmail.com',
                'email' => 'pharmacy@gmail.com',
                'role' => 'pharmacy',
                'password' => Hash::make('12345678'), // Hashed password
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}