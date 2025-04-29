<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCategories(): Collection
    {
        return $this->repository->all();
    }

    public function createCategory(array $data): array
    {
        $data['created_by'] = Auth::id();
        $category = $this->repository->create($data);

        return [
            'success' => true,
            'data' => $category,
            'message' => 'Categoria criada com sucesso'
        ];
    }

    public function updateCategory(int $id, array $data): array
    {
        $updated = $this->repository->update($id, $data);

        return [
            'success' => $updated,
            'message' => $updated ? 'Categoria atualizada com sucesso' : 'Falha ao atualizar categoria'
        ];
    }

    public function deleteCategory(int $id): array
    {
        $deleted = $this->repository->delete($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Categoria removida com sucesso' : 'Falha ao remover categoria'
        ];
    }
}