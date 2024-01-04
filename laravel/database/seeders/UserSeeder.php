<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate();
        $roleAdmin = Role::where('slug', 'supper-admin')->first();
        $admin = [
            'user_name' => 'admin@',
            'is_active' => 1,
            'is_admin' => 'supperAdmin',
            'created_at' => now(),
            'updated_at' => now(),
            'password' => Hash::make(123456),
        ];
        $admin = User::create($admin);
        $admin->roles()->attach($roleAdmin->id);
    }
}
