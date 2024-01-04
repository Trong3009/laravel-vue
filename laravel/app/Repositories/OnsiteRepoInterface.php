<?php

namespace App\Repositories;

interface OnsiteRepoInterface extends BaseRepoInterface
{
    public function model(): string;
    public function index(array $inputs): mixed;
    public function createOnsite(array $inputs): mixed;
    public function updateOnsite($id, array $inputs): array;
    public function pluckModel(): mixed;
    public function deleteOnsite($id): bool;
}