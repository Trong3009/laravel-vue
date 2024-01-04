<?php

namespace App\Repositories;


interface PermissionRepoInterface extends BaseRepoInterface
{
    /**
     * @return string
     */
    public function model(): string;

    public function getList();
}
