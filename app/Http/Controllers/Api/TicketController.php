<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    protected $service;

    public function __construct(TicketService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'category_id', 'created_by']);
        $tickets = $this->service->getAllTickets($filters);
        return response()->json($tickets);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $result = $this->service->createTicket($request->all());
        return response()->json($result['data'], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:aberto,em_progresso,resolvido',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $result = $this->service->updateTicket($id, $request->all());
        return response()->json($result);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->service->deleteTicket($id);
        return response()->json(null, 204);
    }
}
