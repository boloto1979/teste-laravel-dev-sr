<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_list_all_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'created_by',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJsonCount(3);
    }

    public function test_create_category()
    {
        $data = [
            'name' => 'Nova Categoria'
        ];

        $response = $this->postJson('/api/categories', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'created_by',
                'created_at',
                'updated_at'
            ])
            ->assertJson([
                'name' => 'Nova Categoria',
                'created_by' => $this->user->id
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Nova Categoria',
            'created_by' => $this->user->id
        ]);
    }

    public function test_update_category()
    {
        $category = Category::factory()->create([
            'created_by' => $this->user->id
        ]);

        $data = [
            'name' => 'Categoria Atualizada'
        ];

        $response = $this->putJson("/api/categories/{$category->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Categoria atualizada com sucesso'
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Categoria Atualizada'
        ]);
    }

    public function test_delete_category()
    {
        $category = Category::factory()->create([
            'created_by' => $this->user->id
        ]);

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->postJson('/api/categories', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_validation_errors_on_update()
    {
        $category = Category::factory()->create([
            'created_by' => $this->user->id
        ]);

        $response = $this->putJson("/api/categories/{$category->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}
