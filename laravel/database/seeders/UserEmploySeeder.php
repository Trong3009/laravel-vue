<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserEmploySeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $users = [];
        $chucvu = ['giám đốc', 'Dev', 'BA', 'Tester'];
        $userDetails = [];
        for ($i = 2; $i <= 20; $i++) {
            $users[] = [
                'user_name' => Str::random(6),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'password' => Hash::make(123456),
            ];
            $userDetails[] = [
                'user_id' => $i,
                'code' => 'NV-000' . $faker->unique()->numberBetween(1,9999),
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'phone' => '098' . rand(6666666, 9999999),
                'birthday' => $faker->date,
                'gender' => rand(0, 1),
                'permanent_address' => '',
                'residence_address' => $faker->address,
                'domicile' => '',
                'nationality' => '',
                'nation' => '',
                'religion' => '',
                'marital_status' => 0,
                'name_father' => $faker->name,
                'name_mother' => $faker->name,
                'name_wife_husband' => $faker->name,
                'birthday_father' => '',
                'birthday_mother' => '',
                'birthday_wife_husband' => '',
                'note_user' => '',
                'person_contact' => '',
                'person_address' => '',
                'person_email' => '',
                'person_phone' => '',
                'vehicle_type' => '',
                'vehicle_name' => '',
                'vehicle_number' => '',
                'type_of_documents' => 0,
                'identity_card' => '',
                'date_identity_card' => null,
                'address_identity_card' => '',
                'is_member_communist' => 0,
                'number_member_communist' => '',
                'date_communist' => null,
                'address_communist' => $faker->address,
                'health_condition' => '',
                'height' => '',
                'weight' => '',
                'note_health_condition' => '',
                'bank_number' => '',
                'bank_name' => '',
                'bank_branch' => '',
                'bank_account' => '',
                'transfer_level' => null,
                'training_units' => '',
                'specialize' => '',
                'probation_day' => null,
                'official_day' => null,
                'position' => $chucvu[array_rand($chucvu)],
                'job_title' => '',
                'work_note' => '',
                'work_status' => rand(1, 3),
                'quit_date' => null,
                'quit_reason' => '',
                'basic_salary' => 8000000,
                'responsibility_salary' => null,
                'meal_allowance' => null,
                'travel_allowance' => null,
                'insurance_salary' => null,
                'insurance_amount' => null,
            ];
        }
        DB::table('users')->insert($users);
        DB::table('user_details')->insert($userDetails);
    }
}
