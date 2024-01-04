<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class OtVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        DB::table('over_time_votes')->truncate();
        $otTime = [];
        for ($i = 1; $i < 3; $i ++) {
            $otTime[] = [
                'name' => $faker->name,
                'date_ot' => now(),
                'object' => 'hrm',
                'date_type' => 'Ngày thường',
                'shift' => 'ngay',
                'start_time' => '10:00',
                'end_time' => '11:00',
                'total_time' => 1,
                'multi_time' => '150%',
                'total_multi' => 150,
                'client' => 'nguyen van a',
                'description' => 'mo ta',
                'status' => 1,
                'approved_by_user' => 'Nham NT',
                'reason_approval' => 'LY DO',
                'approved_time' => now(),
                'report_ot' => 'bao cao',
                'user_details_id' => $i,
            ];
        }

        DB::table('over_time_votes')->insert($otTime);
    }
}
