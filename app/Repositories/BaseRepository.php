<?php

namespace App\Repositories;

use App\Exceptions\NoModelDefinedException;
use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Foundation\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @throws NoModelDefinedException
     */
    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getAll()
    {
        return $this->model->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $request): bool
    {
        $model = $this->findWhereFirst('id', $id);

        if(!$model) {
            return false;
        }

        return $model->update($request);
    }

    public function updateSingle($id, $field, $value): bool
    {
        $model = $this->findById($id);

        if(!$model) {
            return false;
        }

        $data[$field] = $value;
        return $model->update($data);
    }

    public function updateByUuid(string $uuid, array $request): bool
    {
        $model = $this->findWhereFirst('uuid', $uuid);

        if(!$model) {
            return false;
        }

        return $model->update($request);
    }

    public function updateSingleByUuid(string $uuid, $field, $value): bool
    {
        $model = $this->findWhereFirst('uuid', $uuid);

        if(!$model) {
            return false;
        }

        $data[$field] = $value;
        return $model->update($data);
    }

    public function findById(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->model->toArray();
    }

    public function first()
    {
        $this->model = $this->model->first();

        return $this;
    }

    public function firstOrFail()
    {
        $this->model = $this->model->firstOrFail();

        return $this;
    }

    public function select(...$fields)
    {
        return $this->model
            ->select($fields)
            ->get();
    }

    public function selectFields(...$fields)
    {
        $this->model = $this->model->select($fields);

        return $this;
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findWhereColumn($column, $value)
    {
        $this->model = $this->model->where($column, $value);

        return $this;
    }

    public function findWhere($column, $value)
    {
        return $this->model
            ->where($column, $value)
            ->get();
    }

    public function findWhereNoGet($column, $value)
    {
        return $this->model
            ->where($column, $value);
    }

    public function findWhereFirst(string $column, mixed $value)
    {
        return $this->model
            ->where($column, $value)
            ->first();
    }

    public function relationships(...$relationships)
    {
        $this->model = $this->model->with($relationships);

        return $this;
    }

    public function orderBy($column, $order = 'DESC')
    {
        $this->model = $this->model->orderBy($column, $order);

        return $this;
    }

    public function paginate($totalPage = 15)
    {
        return $this->model->paginate($totalPage);
    }

    /**
     * @return Application|mixed
     * @throws NoModelDefinedException
     */
    public function resolveModel()
    {
        if (!method_exists($this, 'model')) {
            throw new NoModelDefinedException();
        }

        return app($this->model());
    }
}
