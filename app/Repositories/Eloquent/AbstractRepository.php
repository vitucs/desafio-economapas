<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    public function all(string $column = 'id', string $order = 'ASC'): Collection
    {
        return $this->model->orderBy($column, $order)->get();
    }

    public function paginate(int $paginate = 10, string $column = 'id', string $order = 'ASC'): LengthAwarePaginator
    {
        return $this->model->orderBy($column, $order)->paginate($paginate);
    }

    public function findWhereLike(array $columns, string $search, string $column = 'id', string $order = 'ASC'): Collection
    {
        $query = $this->model;
        foreach ($columns as $key => $value) {
            $query = $query->orWhere($value, 'like', '%' . $search . '%');
        }
        return $query->orderBy($column, $order)->get();
    }

    protected function resolveModel()
    {
        return app($this->model);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, int $id)
    {
        $register = $this->find($id);
        if ($register) {
            return $register->update($data);
        } else {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $register = $this->find($id);
        if ($register) {
            return (bool)$register->delete();
        } else {
            return false;
        }
    }

    public function query()
    {
        return $this->model->query();
    }
}
