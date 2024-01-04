<?php

namespace App\Repositories\Impl;

use App\Models\Role;
use App\Repositories\RoleRepoInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleRepository extends BaseRepository implements RoleRepoInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Role::class;
    }


    public function getList(array $inputs): mixed
    {
        if (!empty($inputs['per_page'])) {
            $perPage = $inputs['per_page'];
        } else {
            $perPage = parent::PER_PAGE;
        }
        $conditions['per_page'] = $perPage;
        $conditions['fields'] = '*';

        $conditions['sort'] = [
            'id' => 'desc'
        ];

        // tìm kiếm like trên input name="keyword"
        if (isset($inputs['filter'])) {
            $filterObject = json_decode($inputs['filter'], true);
            if (!empty($filterObject)) {
                foreach ($filterObject as $key => $value) {
                    if ($key == 'keyword') {
                        $conditions['filter']['condition_like'] = [
                            'name' => $value,
                        ];
                    }
                }
            }
        }
        // sắp xếp
        if (isset($inputs['sort'])) {
            $conditions['sort'] = json_decode($inputs['sort'], true);
        }

        $this->filterBuilder = $this->model->where('slug', '!=', 'supper-admin')->with('permissions');

        return $this->getListIntoTable($conditions, $perPage);
    }


    public function getDetail($id)
    {
        $role = Role::with([
            'permissions' => function ($query) {
                $query->select('name', 'slug', 'permission_id');
            }
        ])->where('id', $id)->first();
        if (empty($role)) {
            throw new NotFoundHttpException('Vai trò không tồn tại');
        }

        return $role;
    }

    /**
     * @param array $inputs
     * @return mixed
     * @throws \Exception
     */
    public function createRole(array $inputs): mixed
    {
        DB::beginTransaction();
        try {
            $role = Role::create([
                "name" => $inputs['name'],
                "description" => $inputs['description'],
                "slug" => Str::slug($inputs['name'])
            ]);
            $role->permissions()->sync($inputs['permission_id']);
            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param array $inputs
     * @param int $id
     * Cập nhật quyền cho vai trò
     * @throws \Exception
     */
    public function updateRole(array $inputs, int $id)
    {
        $role = Role::find($id);
        if (empty($role)) {
            throw new NotFoundHttpException("vai trò không tồn tại");
        }
        DB::beginTransaction();
        try {
            $role->update([
                "name" => $inputs['name'],
                "description" => $inputs['description'],
                "slug" => Str::slug($inputs['name'])
            ]);
            $role->permissions()->sync($inputs['permission_id']);
            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param $id
     * @throws \Exception Xóa vai trò
     */
    public function deleteRole($id): void
    {
        $role = Role::find($id);
        if (empty($role)) {
            throw new NotFoundHttpException("vai trò không tồn tại");
        }
        if ($role->slug === 'admin') {
            throw new NotFoundHttpException('Vai trò không thể xóa');
        }
        DB::beginTransaction();
        try {
            foreach ($role->permissions as $permission) {
                $role->permissions()->updateExistingPivot($permission->id, ['deleted_at' => now()]);
            }
            $role->delete();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function getAllRoles()
    {
        return Role::where('slug', '!=', 'supper-admin')->select('id', 'name')->get();
    }
}
