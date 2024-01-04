<?php

namespace App\Repositories;

use App\Repositories\Impl\RepositoryException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;

interface RoleRepoInterface extends BaseRepoInterface
{
    /**
     * @return string
     */
    public function model(): string;

    /**
     * @param array $inputs
     * @return mixed
     */
    public function getList(array $inputs): mixed;

    public function getDetail($id);


    /**
     * @param array $inputs
     * @return mixed
     * @throws \Exception
     */
    public function createRole(array $inputs): mixed;

    /**
     * @param array $inputs
     * @param int $id
     * Cập nhật quyền cho vai trò
     *@throws \Exception
     */
    public function updateRole(array $inputs, int $id);

    /**
     * @param $id
     * @throws \Exception Xóa vai trò
     */
    public function deleteRole($id): void;

    public function getAllRoles();
}
