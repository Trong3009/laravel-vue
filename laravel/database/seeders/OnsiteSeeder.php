<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OnsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $faker = Factory::create();
        DB::table('onsites')->truncate();
        $otTime = [];
        for ($i = 1; $i < 3; $i ++) {
            $otTime[] = [
                'name' => 'Trần Văn Ninh',
                'date_onsite' => now(),
                'start_time' => '08:00',
                'end_time' => '11:00',
                'file_onsite' => 'ádsa',
                'location' => 'Giải Phóng',
                'object' => 'HRN',
                'approved_by' => 'Trần đăng Ninh',
                'status' => 2,
                'description' => 'Không có gì để nói',
                'file_image_onsite' => 'sdas',
            ];
        }

        DB::table('onsites')->insert($otTime);
    }
}
