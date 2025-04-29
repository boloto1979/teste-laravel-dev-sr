<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_list_all_tickets()
    {
        Ticket::factory()->count(3)->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/tickets');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'category_id',
                    'created_by',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJsonCount(3);
    }

    public function test_list_tickets_with_filters()
    {
        Ticket::factory()->create([
            'status' => 'aberto',
            'created_by' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        Ticket::factory()->create([
            'status' => 'em_progresso',
            'created_by' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/tickets?status=aberto');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['status' => 'aberto']);
    }

    public function test_create_ticket()
    {
        $data = [
            'title' => 'Novo Ticket',
            'description' => 'Descrição do ticket',
            'category_id' => $this->category->id
        ];

        $response = $this->postJson('/api/tickets', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'status',
                'category_id',
                'created_by',
                'created_at',
                'updated_at'
            ])
            ->assertJson([
                'title' => 'Novo Ticket',
                'description' => 'Descrição do ticket',
                'status' => 'aberto',
                'category_id' => $this->category->id,
                'created_by' => $this->user->id
            ]);

        $this->assertDatabaseHas('tickets', [
            'title' => 'Novo Ticket',
            'description' => 'Descrição do ticket',
            'status' => 'aberto',
            'category_id' => $this->category->id,
            'created_by' => $this->user->id
        ]);
    }

    public function test_update_ticket()
    {
        $ticket = Ticket::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $data = [
            'title' => 'Ticket Atualizado',
            'status' => 'em_progresso'
        ];

        $response = $this->putJson("/api/tickets/{$ticket->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Ticket atualizado com sucesso'
            ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'title' => 'Ticket Atualizado',
            'status' => 'em_progresso'
        ]);
    }

    public function test_delete_ticket()
    {
        $ticket = Ticket::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->deleteJson("/api/tickets/{$ticket->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->postJson('/api/tickets', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'description', 'category_id']);
    }

    public function test_validation_errors_on_update()
    {
        $ticket = Ticket::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->putJson("/api/tickets/{$ticket->id}", [
            'status' => 'status_invalido'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
