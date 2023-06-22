<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stories')->insert([
            [
                'title' => 'Fietsvakantie naar Italië',
                'description' => fake()->text(),
                'user_id' => 1,
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
