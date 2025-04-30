<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $openTickets = Ticket::where('status', 'aberto')->count();
        $resolvedTickets = Ticket::where('status', 'resolvido')->count();
        $inProgressTickets = Ticket::where('status', 'em_progresso')->count();

        $recentTickets = Ticket::with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('welcome', compact(
            'openTickets',
            'resolvedTickets',
            'inProgressTickets',
            'recentTickets'
        ));
    }
}
