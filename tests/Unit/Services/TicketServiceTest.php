<?php

namespace Tests\Unit\Services;

use App\Models\Ticket;
use App\Repositories\TicketRepository;
use App\Services\TicketService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class TicketServiceTest extends TestCase
{
    protected $repository;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(TicketRepository::class);
        $this->service = new TicketService($this->repository);
    }

    public function test_get_all_tickets()
    {
        $tickets = new Collection([
            new Ticket(['title' => 'Ticket 1']),
            new Ticket(['title' => 'Ticket 2'])
        ]);

        $this->repository
            ->shouldReceive('all')
            ->once()
            ->with([])
            ->andReturn($tickets);

        $result = $this->service->getAllTickets();

        $this->assertEquals($tickets, $result);
    }

    public function test_get_all_tickets_with_filters()
    {
        $filters = ['status' => 'aberto'];
        $tickets = new Collection([
            new Ticket(['title' => 'Ticket 1', 'status' => 'aberto'])
        ]);

        $this->repository
            ->shouldReceive('all')
            ->once()
            ->with($filters)
            ->andReturn($tickets);

        $result = $this->service->getAllTickets($filters);

        $this->assertEquals($tickets, $result);
    }

    public function test_create_ticket()
    {
        $data = [
            'title' => 'Novo Ticket',
            'description' => 'Descrição do ticket',
            'category_id' => 1
        ];

        $ticket = new Ticket($data);

        Auth::shouldReceive('id')->once()->andReturn(1);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with([
                'title' => 'Novo Ticket',
                'description' => 'Descrição do ticket',
                'category_id' => 1,
                'created_by' => 1,
                'status' => 'aberto'
            ])
            ->andReturn($ticket);

        $result = $this->service->createTicket($data);

        $this->assertTrue($result['success']);
        $this->assertEquals($ticket, $result['data']);
        $this->assertEquals('Ticket criado com sucesso', $result['message']);
    }

    public function test_update_ticket()
    {
        $id = 1;
        $data = [
            'title' => 'Ticket Atualizado',
            'status' => 'em_progresso'
        ];

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andReturn(true);

        $result = $this->service->updateTicket($id, $data);

        $this->assertTrue($result['success']);
        $this->assertEquals('Ticket atualizado com sucesso', $result['message']);
    }

    public function test_delete_ticket()
    {
        $id = 1;

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->service->deleteTicket($id);

        $this->assertTrue($result['success']);
        $this->assertEquals('Ticket removido com sucesso', $result['message']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
