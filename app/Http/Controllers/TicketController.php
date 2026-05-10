<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;


class TicketController extends Controller
{
    
    public function index()
    {
        $query = Ticket::with('user');

        
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return Inertia::render('Tickets/Index', [
            'tickets' => $query->latest()->get()
        ]);
    }
public function create()
{
    return Inertia::render('Tickets/Create');
}
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'priority' => 'required|string',
        ]);

        auth()->user()->tickets()->create($validated);

        return redirect()->route('tickets.index');
    }


    public function show(Ticket $ticket)
{
    
    if (auth()->user()->role !== 'admin' && $ticket->user_id !== auth()->id()) {
        abort(403);
    }

    
    return Inertia::render('Tickets/Show', [
        'ticket' => $ticket->load(['user', 'comments.user']),
    ]);
}


public function edit(Ticket $ticket)
{
 
    if (auth()->user()->role !== 'admin' && $ticket->user_id !== auth()->id()) {
        abort(403);
    }

    return Inertia::render('Tickets/Edit', [
        'ticket' => $ticket
    ]);
}

// දත්ත Update කිරීම
public function update(Request $request, Ticket $ticket)
{
    $rules = [
        'subject' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|string',
        'priority' => 'required|string',
    ];

    
    if (auth()->user()->role === 'admin') {
        $rules['status'] = 'required|string';
    }

    $validated = $request->validate($rules);
    $ticket->update($validated);

    return redirect()->route('tickets.index')->with('message', 'Ticket updated successfully.');
}
}
