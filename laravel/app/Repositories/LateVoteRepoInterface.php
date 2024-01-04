<?php

namespace App\Repositories;

interface LateVoteRepoInterface extends BaseRepoInterface
{
    public function model(): string;

    public function index(array $inputs): mixed;

    public function createLateVote(array $inputs): array;

    public function updateLateVote($id, array $inputs): array;

    public function pluckModel(): mixed;

    public function updateWorkStatus($id, $status): bool;

    public function deleteLateVote($id,): bool;
}