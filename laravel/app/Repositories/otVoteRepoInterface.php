<?php

namespace App\Repositories;

interface OtVoteRepoInterface extends BaseRepoInterface
{
    public function model(): string;
    public function index(array $inputs): mixed;
    public function createOT(array $inputs): mixed;
    public function updateOT($id, array $inputs): array;
    public function pluckModel(): mixed;
    public function deleteOT($id): void;
}