<?php

namespace App\Repositories\Impl;

use App\Repositories\BaseRepoInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository implements BaseRepoInterface
{
    /** @var int so record tren trang */
    const PER_PAGE = 20;

    /** @var int trang thai lam viec */
    const WORKING_STATUS = 1;
    /** @var int tai khoan hoat dong */
    const IS_ACTIVE = 1;
    /** @var int Phiếu chờ duyệt */
    const VOTE_STATUS = 2;
    public $filterBuilder;

    public Model $model;

    /**
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * @return Model
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function makeModel(): Model
    {
        $model = app()->make($this->model());
        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of " . Model::class);
        }

        return $this->model = $model;
    }

    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * Create a new model and return the instance.
     *
     * @param array $inputs
     *
     * @return Model|null instance
     */
    public function store(array $inputs): ?Model
    {
        return $this->model->create($inputs);
    }

    /**
     * @param $ids
     * @param int $dem
     * @return bool|int
     * @throws \Exception
     */
    public function delete($ids, int $dem = 0): bool|int
    {
        foreach ($ids as $id) {
            $result = $this->model->find($id);
            if (!$result) {
                continue;
            }
            DB::beginTransaction();
            try {
                $result->delete();
                $dem++;
                DB::commit();
            } catch (\Exception $e) {
                Log::info("Error delete potential " . $e->getMessage());
                DB::rollBack();
            }
        }

        if ($dem == 0) {
            return false;
        } else {
            return $dem;
        }
    }

    /**
     * FindOrFail Model and return the instance.
     * @param int $id
     * @return Model|Collection
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Model|Collection
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update the model in the database.
     * @param int $id
     * @param array $inputs
     * @return bool
     */
    public function update(int $id, array $inputs): bool
    {
        return $this->getById($id)->update($inputs);
    }

    /**
     * Delete the model from the database.
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return $this->getById($id)->delete();
    }

    /**
     * @param array $conditions
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function getListIntoTable(array $conditions, $perPage): LengthAwarePaginator
    {
        if (!empty($conditions['filter'])) {
            $this->filterBuilder = $this->whereBuilder($conditions['filter']);
        }
        if (!empty($conditions['sort'])) {
            $this->filterBuilder = $this->orderBuilder($conditions['sort']);
        }

        return $this->filterBuilder->paginate($perPage, $conditions['fields']);
    }
    /**
     * @param $fields
     * @return Collection|Model[]
     */
    public function getAll($fields): array|Collection
    {
        return $this->model->all($fields);
    }

    /**
     * @param array $conditions
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function paginateCustom(array $conditions, int $limit = 15): LengthAwarePaginator
    {
        if (!empty($conditions['filter'])) {
            $this->filterBuilder = $this->whereBuilder($conditions['filter']);
        }
        if (!empty($conditions['sort'])) {
            $this->filterBuilder = $this->orderBuilder($conditions['sort']);
        }
        if (isset($conditions['per_page'])) {
            $limit = $conditions['per_page'];
        }
        if (isset($conditions['groupBy'])) {
            $this->filterBuilder = $this->groupByBuilder($conditions['groupBy']);
        }

        return $this->filterBuilder->paginate(
            $limit,
            $conditions['fields'],
            isset($conditions['namePages']) ? $conditions['namePages'] : "page");
    }

    /**
     * @param array $conditions
     * @return mixed
     */
    public function queryWithoutPaginate(array $conditions): mixed
    {
        if (!empty($conditions['filter'])) {
            $this->filterBuilder = $this->whereBuilder($conditions['filter']);
        }

        if (!empty($conditions['sort'])) {
            $this->orderBuilder($conditions['sort']);
        }

        if (isset($conditions['groupBy'])) {
            $this->filterBuilder = $this->groupByBuilder($conditions['groupBy']);
        }

        return $this->filterBuilder->select($conditions['fields'])->get();
    }

    /**
     * @param array $conditions
     * @return Builder
     */
    public function whereBuilder(array $conditions): Builder
    {
        foreach ($conditions as $k => $v) {
            if ($k == 'condition_like') {
                $this->filterBuilder = $this->filterBuilder->where(function ($query) use ($v) {
                    foreach ($v as $key => $col) {
                        $query->orWhere($key, 'like', '%' . $col . '%');
                    }
                });
                continue;
            }
            if ($k == 'where_columns') {
                $this->filterBuilder = $this->filterBuilder->where(function ($query) use ($v) {
                    foreach ($v as $key => $col) {
                        if (!empty($col)) {
                            $query->where($key, $col);
                        }
                    }
                });
                continue;
            }
            if ($k == 'condition_start_date') {
                $this->filterBuilder = $this->filterBuilder->where(function ($query) use ($v) {
                    foreach ($v as $key => $val) {
                        $new = strtotime($val);
                        $query->Where($key, '>=', date("Y-m-d", $new));
                    }
                });
                continue;
            }
            if ($k == 'where_date_end') {
                $this->filterBuilder = $this->filterBuilder->where(function ($query) use ($v) {
                    foreach ($v as $key => $val) {
                        $new = strtotime($val) + 24 * 60 * 60 + 1;
                        $query->Where($key, '<', date("Y-m-d", $new));
                    }
                });
                continue;
            }
            if ($k == 'where_id') {
                foreach ($v as $key => $val) {
                    $this->filterBuilder = $this->filterBuilder->where($key, $val);
                }
                continue;
            }
            if ($k == 'where_check') {
                $this->filterBuilder = $this->filterBuilder->where(function ($query) use ($v) {
                    foreach ($v as $key => $val) {
                        $query->Where($key, '>', 0);
                    }
                });
                continue;
            }
            if ($k == 'where_status') {
                $this->filterBuilder = $this->filterBuilder->where(function ($query) use ($v) {
                    foreach ($v as $key => $val) {
                        $query->Where($key, $val);
                    }
                });
                continue;
            }
        }

        return $this->filterBuilder;
    }

    /**
     * @param array $conditions
     * @return Builder
     */
    public function groupByBuilder(array $conditions): Builder
    {
        if (!$this->filterBuilder) {
            $this->filterBuilder = $this->model;
        }
        if (empty($conditions)) {
            return $this->filterBuilder;
        }
        if (!empty($conditions['groupBy'])) {
            foreach ($conditions['groupBy'] as $v) {
                $this->filterBuilder = $this->filterBuilder->select($conditions['fields'], DB::raw('count(*) as total'))->skip(0)->take(200)->groupBy($conditions['fields']);
            }
        }
        return $this->filterBuilder;
    }

    /**
     * @param array $sorts
     * @return Builder
     */
    public function orderBuilder(array $sorts): Builder
    {
        if (!empty($sorts)) {
            foreach ($sorts as $k => $v) {
                $this->filterBuilder = $this->filterBuilder->orderBy($k, $v);
            }
        }

        return $this->filterBuilder;
    }
}
