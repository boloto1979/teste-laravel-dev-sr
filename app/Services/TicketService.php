<?php

namespace App\Services;

use App\Repositories\TicketRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    protected $repository;

    public function __construct(TicketRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTickets(array $filters = []): Collection
    {
        return $this->repository->all($filters);
    }

    public function createTicket(array $data): array
    {
        $data['created_by'] = Auth::id();
        $data['status'] = 'aberto';

        $ticket = $this->repository->create($data);

        return [
            'success' => true,
            'data' => $ticket,
            'message' => 'Ticket criado com sucesso'
        ];
    }

    public function updateTicket(int $id, array $data): array
    {
        $updated = $this->repository->update($id, $data);

        return [
            'success' => $updated,
            'message' => $updated ? 'Ticket atualizado com sucesso' : 'Falha ao atualizar ticket'
        ];
    }

    public function deleteTicket(int $id): array
    {
        $deleted = $this->repository->delete($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Ticket removido com sucesso' : 'Falha ao remover ticket'
        ];
    }
}