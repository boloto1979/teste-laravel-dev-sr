<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    protected $repository;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(CategoryRepository::class);
        $this->service = new CategoryService($this->repository);
    }

    public function test_get_all_categories()
    {
        $categories = new Collection([
            new Category(['name' => 'Categoria 1']),
            new Category(['name' => 'Categoria 2'])
        ]);

        $this->repository
            ->shouldReceive('all')
            ->once()
            ->andReturn($categories);

        $result = $this->service->getAllCategories();

        $this->assertEquals($categories, $result);
    }

    public function test_create_category()
    {
        $data = ['name' => 'Nova Categoria'];
        $category = new Category($data);

        Auth::shouldReceive('id')->once()->andReturn(1);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with(['name' => 'Nova Categoria', 'created_by' => 1])
            ->andReturn($category);

        $result = $this->service->createCategory($data);

        $this->assertTrue($result['success']);
        $this->assertEquals($category, $result['data']);
        $this->assertEquals('Categoria criada com sucesso', $result['message']);
    }

    public function test_update_category()
    {
        $id = 1;
        $data = ['name' => 'Categoria Atualizada'];

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andReturn(true);

        $result = $this->service->updateCategory($id, $data);

        $this->assertTrue($result['success']);
        $this->assertEquals('Categoria atualizada com sucesso', $result['message']);
    }

    public function test_delete_category()
    {
        $id = 1;

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->service->deleteCategory($id);

        $this->assertTrue($result['success']);
        $this->assertEquals('Categoria removida com sucesso', $result['message']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}