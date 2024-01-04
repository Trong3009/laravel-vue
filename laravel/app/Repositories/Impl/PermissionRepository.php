<?php

namespace App\Repositories\Impl;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\PermissionRepoInterface;
use App\Repositories\RoleRepoInterface;

class PermissionRepository extends BaseRepository implements PermissionRepoInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Permission::class;
    }

    public function getList(): array
    {
        $permissions = Permission::all();
        $data = [];
        $dataOutput = [];
        foreach ($permissions as $key => $permission) {
            if ($permission->parent_id == 0) {
                $data[$permission->id] = $permission->name;
                unset($permissions[$key]);
            }
        }
        foreach ($permissions as $permission) {
            foreach ($data as $k => $val) {
                if ($permission->parent_id == $k) {
                    $array = explode('-', $permission->slug);
                    $slug = end($array);
                    $dataOutput[__($val)][] = [
                        'permission_id' => $permission->id,
                        'name' => __($permission->name),
                        'slug' => __($slug)
                    ];
                }
            }
        }

        return $dataOutput;
    }
}
