<?php

namespace App\Repositories;

interface PauseVoteRepoInterface extends BaseRepoInterface
{
    public function model(): string;

    public function index(array $inputs): mixed;

    public function createPauseVote(array $inputs): mixed;

    public function updatePauseVote($id, array $inputs): array;

    public function pluckModel(): mixed;

    public function deletePauseVote($id): void;
}