<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * Find a model by its primary key.
     */
    public function find(int $id): ?Model;

    /**
     * Find a model by its primary key or throw an exception.
     */
    public function findOrFail(int $id): Model;

    /**
     * Get all models.
     */
    public function all(): Collection;

    /**
     * Get paginated results.
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Create a new model.
     */
    public function create(array $data): Model;

    /**
     * Update a model.
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a model.
     */
    public function delete(int $id): bool;

    /**
     * Add a where clause.
     */
    public function where(string $column, $operator, $value = null): Builder;

    /**
     * Add relationships to eager load.
     */
    public function with(array $relations): self;

    /**
     * Add a withCount clause.
     */
    public function withCount(array $relations): self;

    /**
     * Order by column.
     */
    public function orderBy(string $column, string $direction = 'asc'): self;

    /**
     * Get query builder.
     */
    public function query(): Builder;

    /**
     * Begin a new query.
     */
    public function newQuery(): Builder;
}
