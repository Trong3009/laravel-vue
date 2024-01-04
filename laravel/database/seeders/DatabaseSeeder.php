<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(UserEmploySeeder::class);
        $this->call(OtVoucherSeeder::class);
        $this->call(LateVoucherSeeder::class);
        $this->call(PauseVoucherSeeder::class);
        $this->call(OnsiteSeeder::class);
        $this->call(RemoteSeeder::class);
    }
}
