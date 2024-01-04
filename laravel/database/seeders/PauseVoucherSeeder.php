<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class PauseVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        DB::table('pause_votes')->truncate();
        $otTime = [];
        for ($i = 1; $i < 5; $i ++) {
            $otTime[] = [
                'name'=>'Trần Đăng Ninh',
                'since_session'=>'sáng',
                'todate_session'=>'sáng',
                'number_days'=>3,
                'reason'=>'ốm',
                'status'=>2,
                'types_break'=>'Nghỉ phép',
                'salary_percentege'=>'100%',
                'history_time'=>"",
                'reason_approved'=>'',
                'approved_by'=>'nhamnt',
                'since_date'=>now(),
                'todate_date'=>now(),
                'users_detail_id'=>$i,
            ];
        }

        DB::table('pause_votes')->insert($otTime);
    }   
}
