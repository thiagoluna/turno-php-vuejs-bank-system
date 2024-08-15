<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function getAll();
    public function create(array $data);
    public function update(int $id, array $request): bool;
    public function updateSingle($id, $field, $value): bool;
    public function updateByUuid(string $uuid, array $request): bool;
    public function updateSingleByUuid(string $uuid, $field, $value): bool;
    public function toArray(): array;
    public function findById(string $id);
    public function first();
    public function firstOrFail();
    public function select(...$fields);
    public function selectFields(...$fields);
    public function findOrFail($id);
    public function findWhereColumn($column, $value);
    public function findWhere($column, $value);
    public function findWhereNoGet($column, $value);
    public function findWhereFirst(string $column, mixed $value);
    public function relationships(...$relationships);
    public function orderBy($column, $order = 'DESC');
    public function paginate($totalPage = 15);
    public function resolveModel();
}
