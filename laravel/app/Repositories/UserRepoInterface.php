<?php

namespace App\Repositories;

use App\Repositories\Impl\RepositoryException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;

interface UserRepoInterface extends BaseRepoInterface
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

    public function createUser(array $inputs);

    public function pluckModel(): mixed;

    /**
     * @param int $id
     * @param array $inputs
     * @return mixed
     */
    public function updateUser(int $id, array $inputs): mixed;

    public function lockAndUnlock(int $id, array $inputs);
}
