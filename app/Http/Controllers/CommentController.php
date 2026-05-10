<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        $ticket->comments()->create([
            'body' => $validated['body'],
            'user_id' => auth()->id(),
        ]);

        return back(); // Inertia මගින් පිටුව reload නොවී දත්ත යාවත්කාලීන කරයි
    }
}