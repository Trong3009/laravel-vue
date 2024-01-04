<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
class LateVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        DB::table('late_votes')->truncate();
        $otTime = [];
        for ($i = 1; $i < 3; $i ++) {
            $otTime[] = [
                'name'=>$faker->name,
                'late_date'=>now(),
                'shift_job'=>"sáng",
                'start_time'=>"08:00:00",
                'end_time'=>"10:00:00",
                'number_minute'=>180,
                'object'=>'dự án',
                'reason'=>':D',
                'status'=>2,
                'approved_by'=>'nhâmtt',
                'reason_approved'=>'',
                'time_approved'=>now(),
                'user_details_id'=>$i
            ];
        }

        DB::table('late_votes')->insert($otTime);
    }
}
