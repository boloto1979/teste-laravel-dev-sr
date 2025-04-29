<?php

namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class TicketRepository
{
    protected $model;

    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }

    public function all(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (!empty($filters)) {
            $this->applyFilters($query, $filters);
        }

        return $query->with(['category', 'creator'])->get();
    }

    public function create(array $data): Ticket
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Ticket
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Ticket
    {
        return $this->model->findOrFail($id);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->findOrFail($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['created_by'])) {
            $query->where('created_by', $filters['created_by']);
        }
    }
}