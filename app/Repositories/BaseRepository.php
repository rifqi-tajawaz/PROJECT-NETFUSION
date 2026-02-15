<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;
    protected Builder $query;
    protected bool $enableCache = false;
    protected int $cacheDuration = 3600; // 1 hour

    /**
     * Create a new repository instance.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->query = $model->newQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Model
    {
        if ($this->enableCache) {
            return Cache::remember(
                $this->getCacheKey("find_{$id}"),
                $this->cacheDuration,
                fn() => $this->query->find($id)
            );
        }

        return $this->query->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findOrFail(int $id): Model
    {
        if ($this->enableCache) {
            return Cache::remember(
                $this->getCacheKey("find_{$id}"),
                $this->cacheDuration,
                fn() => $this->query->findOrFail($id)
            );
        }

        return $this->query->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function all(): Collection
    {
        if ($this->enableCache) {
            return Cache::remember(
                $this->getCacheKey('all'),
                $this->cacheDuration,
                fn() => $this->query->get()
            );
        }

        return $this->query->get();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query->paginate($perPage, $columns);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Model
    {
        $model = $this->query->create($data);

        if ($this->enableCache) {
            $this->clearCache();
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): Model
    {
        $model = $this->findOrFail($id);
        $model->update($data);

        if ($this->enableCache) {
            $this->clearCache();
        }

        return $model->fresh();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $model = $this->findOrFail($id);
        $result = $model->delete();

        if ($this->enableCache) {
            $this->clearCache();
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function where(string $column, $operator, $value = null): Builder
    {
        return $this->query->where($column, $operator, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function with(array $relations): self
    {
        $this->query = $this->query->with($relations);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withCount(array $relations): self
    {
        $this->query = $this->query->withCount($relations);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->query = $this->query->orderBy($column, $direction);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function query(): Builder
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function newQuery(): Builder
    {
        $this->query = $this->model->newQuery();
        return $this->query;
    }

    /**
     * Enable caching for this repository.
     */
    public function enableCache(int $duration = 3600): self
    {
        $this->enableCache = true;
        $this->cacheDuration = $duration;
        return $this;
    }

    /**
     * Disable caching for this repository.
     */
    public function disableCache(): self
    {
        $this->enableCache = false;
        return $this;
    }

    /**
     * Get cache key for this repository.
     */
    protected function getCacheKey(string $suffix): string
    {
        return class_basename($this->model) . ':' . $suffix;
    }

    /**
     * Clear all cache for this repository.
     */
    protected function clearCache(): void
    {
        $pattern = class_basename($this->model) . ':*';
        Cache::forget($pattern);
    }

    /**
     * Find by multiple IDs.
     */
    public function findMany(array $ids): Collection
    {
        return $this->query->findMany($ids);
    }

    /**
     * Find by column value.
     */
    public function findBy(string $column, $value): ?Model
    {
        return $this->query->where($column, $value)->first();
    }

    /**
     * Get count of records.
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * Check if model exists.
     */
    public function exists(int $id): bool
    {
        return $this->query->where($this->model->getKeyName(), $id)->exists();
    }

    /**
     * Get first record.
     */
    public function first(): ?Model
    {
        return $this->query->first();
    }

    /**
     * Get first record or throw exception.
     */
    public function firstOrFail(): Model
    {
        return $this->query->firstOrFail();
    }

    /**
     * Add whereIn clause.
     */
    public function whereIn(string $column, array $values): Builder
    {
        return $this->query->whereIn($column, $values);
    }

    /**
     * Add whereBetween clause.
     */
    public function whereBetween(string $column, array $values): Builder
    {
        return $this->query->whereBetween($column, $values);
    }

    /**
     * Add whereNull clause.
     */
    public function whereNull(string $column): Builder
    {
        return $this->query->whereNull($column);
    }

    /**
     * Add whereNotNull clause.
     */
    public function whereNotNull(string $column): Builder
    {
        return $this->query->whereNotNull($column);
    }

    /**
     * Add search functionality.
     */
    public function search(string $term, array $columns = []): Builder
    {
        if (empty($columns)) {
            $columns = ['name', 'email'];
        }

        return $this->query->where(function ($query) use ($term, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$term}%");
            }
        });
    }
}
