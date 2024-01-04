<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasPermissions
{

    public function hasPermission($checkPermission = null): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        $role = $user->roles()->first();
        if (!$role || !$role->permissions) {
            return false;
        }

        $permissionByUser = $role->permissions->pluck('slug')->toArray();

        $hasPermission = in_array($checkPermission, $permissionByUser);
        if (!$hasPermission) {
            return false;
        }
        return true;
    }
}
