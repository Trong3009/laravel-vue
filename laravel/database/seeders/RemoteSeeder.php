<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RemoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $faker = Factory::create();
        DB::table('remotes')->truncate();
        $otTime = [];
        for ($i = 1; $i < 3; $i ++) {
            $otTime[] = [
                'name' => $faker->name,
                'date_remote' => now(),
                'start_time' => '08:00',
                'end_time' => '11:00',
                'file_remote' => 'ewqeqweqwe',
                'approved_by' => 'Trợ lý Tuấn',
                'status' => 1,
                'description' => 'Ở nh',
                'file_image_remote' => 'cdcdcdcd'
            ];
        }

        DB::table('remotes')->insert($otTime);
    }
}
