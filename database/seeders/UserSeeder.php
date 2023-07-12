<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => "Kian",
                'last_name' => "Vos",
                'email' => 'info@kianvos.nl',
                'password' => Hash::make('password'),
                'birthday' => Carbon::create('2002', '07', '25'),
                'created_at' => Carbon::now(),
            ],
            [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => 'test2@kianvos.nl',
                'password' => Hash::make('password'),
                'birthday' => Carbon::create('2005', '07', '25'),
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
