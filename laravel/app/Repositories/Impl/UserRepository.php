<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Repositories\UserRepoInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserRepository extends BaseRepository implements UserRepoInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param array $inputs
     * @return mixed
     */
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

        // tìm kiếm
        if (isset($inputs['filter'])) {
            $filterObject = json_decode($inputs['filter'], true);
            if (!empty($filterObject)) {
                foreach ($filterObject as $key => $value) {
                    if ($key == 'keyword') {
                        $keywordSearch = $value;
                    }
                    if ($key == 'role_id') {
                        $roleId = $value;
                    }
                }
            }
        }
        // sắp xếp
        if (isset($inputs['sort'])) {
            $conditions['sort'] = json_decode($inputs['sort'], true);
        }

        $this->filterBuilder = $this->model
            ->where('is_admin', '!=', 'supperAdmin')
            ->orWhereNull('is_admin')
            ->with([
            'userDetails' => function ($query) {
                $query->select(
                    'id',
                    'user_id',
                    'name',
                    'code',
                    'email',
                    'phone',
                    'gender',
                    'birthday',
                    'residence_address',
                    'position',
                    'job_title',
                    'avatar',
                    'work_status',
                );
            },
            'userParent' => function ($query) {
                $query->select('id', 'user_name');
            },
            'userParent.userDetails' => function ($query) {
                $query->select('user_id', 'name');
            },
            'roles' => function ($query) {
                $query->select('roles.id', 'roles.name');
            },
        ]);
        if (!empty($keywordSearch)) {
            $this->filterBuilder->whereHas('userDetails', function (Builder $query) use ($keywordSearch) {
                $query->where(function ($subQuery) use ($keywordSearch) {
                    $subQuery->orWhere('name', 'like', "%{$keywordSearch}%")
                        ->orWhere('email', 'like', "%{$keywordSearch}%")
                        ->orWhere('phone', 'like', "%{$keywordSearch}%")
                        ->orWhere('residence_address', 'like', "%{$keywordSearch}%");
                });
            });
        }
        if (!empty($roleId)) {
            $this->filterBuilder->whereHas('roles', function (Builder $query) use ($roleId) {
                $query->where('roles.id', $roleId);
            });
        }

        return $this->getListIntoTable($conditions, $perPage);
    }

    public function createUser(array $inputs)
    {
        $data['user_name'] = $inputs['user_name'];
        $data['parent_id'] = $inputs['parent_id'];
        $data['password'] = Hash::make($inputs['password']);
        $user = User::create($data);
        if (!empty($inputs['role_id'])) {
            $user->roles()->attach($inputs['role_id']);
        }

        return $user;
    }

    public function pluckModel(): mixed
    {
        return User::where('is_active', static::IS_ACTIVE)
        ->whereHas('userDetails', function (Builder $query) {
            $query->where('user_id', '>', 0)
                ->where('work_status', '=', static::WORKING_STATUS);
        })->with(['userDetails' => function ($query) {
            $query->select('user_id', 'name');
        }])
        ->select('id')
        ->get();
    }

    /**
     * @param int $id
     * @param array $inputs
     * @return mixed
     */
    public function updateUser(int $id, array $inputs): mixed
    {
        $data['user_name'] = $inputs['user_name'];
        $data['parent_id'] = $inputs['parent_id'];
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($inputs['password']);
        }
        $user = User::find($id);
        $user->update($data);
        if(!empty($inputs['role_id'])) {
            $user->roles()->sync($inputs['role_id']);
        }

        return $user;
    }

    /**
     * @throws \Exception
     */
    public function lockAndUnlock(int $id, array $inputs)
    {
        if ($inputs['is_active'] == 0) {
            $isActive = 1;
        } else {
            $isActive = 0;
        }
        $user = User::find($id);
        if (!$user) {
            throw new NotFoundHttpException('Tài khoản không tồn tại');
        }
        try {
            DB::beginTransaction();
            $user->update(['is_active' => $isActive]);
            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
