<?php

namespace App\Repositories;

use App\Repositories\Impl\RepositoryException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepoInterface
{
    /**
     * @return Model
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function makeModel(): Model;

    /**
     * Create a new model and return the instance.
     *
     * @param array $inputs
     *
     * @return Model instance
     */
    public function store(array $inputs): ?Model;

    /**
     * @param $ids
     * @param int $dem
     * @return bool|int
     */
    public function delete($ids, int $dem = 0): bool|int;

    /**
     * FindOrFail Model and return the instance.
     * @param int $id
     * @return Model|Collection
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Model|Collection;

    /**
     * Update the model in the database.
     * @param int $id
     * @param array $inputs
     * @return bool
     */
    public function update(int $id, array $inputs): bool;

    /**
     * Delete the model from the database.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool;

    /**
     * @param array $conditions
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function getListIntoTable(array $conditions, $perPage): LengthAwarePaginator;

    /**
     * @param $fields
     * @return Collection|Model[]
     */
    public function getAll($fields): array|Collection;

    /**
     * @param array $conditions
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function paginateCustom(array $conditions, int $limit = 15): LengthAwarePaginator;

    /**
     * @param array $conditions
     * @return mixed
     */
    public function queryWithoutPaginate(array $conditions): mixed;

    /**
     * @param array $conditions
     * @return Builder
     */
    public function whereBuilder(array $conditions): Builder;

    /**
     * @param array $conditions
     * @return Builder
     */
    public function groupByBuilder(array $conditions): Builder;

    /**
     * @param array $sorts
     * @return Builder
     */
    public function orderBuilder(array $sorts): Builder;
}
