<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('permissions.permissionAdmin');
        DB::table('permissions')->truncate();

        foreach ($permissions as $name => $permission) {
            $parent = Permission::create([
                'name' => $name,
                'slug' => $name,
                'parent_id' => 0,
            ]);
            foreach ($permission as $key) {
                Permission::create([
                    'name' => $name.$key,
                    'slug' => $name.'-'.$key,
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
