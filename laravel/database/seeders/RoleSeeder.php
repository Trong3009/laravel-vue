<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->truncate();
        $permissions = Permission::where('parent_id', '!=', 0)->get();
        $roleAdmin = Role::create([
            'name' => 'supperAdmin',
            'slug' => 'supper-admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $idsPermissionAdmin = [];
        foreach ($permissions as $permission) {
            $idsPermissionAdmin[] = $permission->id;
        }
        $roleAdmin->permissions()->attach($idsPermissionAdmin);
    }
}
