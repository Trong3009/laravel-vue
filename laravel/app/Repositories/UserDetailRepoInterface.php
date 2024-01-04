<?php

namespace App\Repositories;

interface UserDetailRepoInterface extends BaseRepoInterface
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

    /**
     * @param array $inputs
     * @return array
     */
    public function createUserDetail(array $inputs): array;

    /**
     * @param $id
     * @param array $inputs
     * @return array
     */
    public function updateUserDetail($id, array $inputs): array;

    public function pluckModel(): mixed;

    /**
     * @param $id
     * @param $status
     * @return bool
     */
    public function updateWorkStatus($id, $status): bool;

    /**
     * @param $id
     * @return void
     */
    public function deleteByUser($id): void;

    /**
     * @param $request
     */
    public function createMultipleUserDetail($request): string;
}
