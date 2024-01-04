<?php

namespace App\Repositories;

interface RemoteRepoInterface extends BaseRepoInterface
{
    public function model(): string;

    public function index(array $inputs): mixed;

    public function createRemote(array $inputs): mixed;

    public function updateRemote($id, array $inputs):array;

    public function pluckModel(): mixed;
    
    public function deleteRemote($id):void;
}