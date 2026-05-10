<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 

class CommentController extends Controller
{
    // public function store(Request $request, Ticket $ticket)
    // {
    //     $request->validate([
    //         'body' => 'required|string',
    //         'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
    //     ]);

    //     $comment = new Comment();
    //     $comment->body = $request->body;
    //     $comment->ticket_id = $ticket->id;
    //     $comment->user_id = auth()->id();

    //     if ($request->hasFile('attachment')) {
    //         $path = $request->file('attachment')->store('ticket-attachments', 'public');
    //         $comment->file_path = $path;
    //     }

    //     $comment->save();

    //     return back();
    // }

    public function store(Request $request, Ticket $ticket)
{
    $request->validate([
        'body' => 'required|string',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $comment = new Comment();
    $comment->body = $request->body;
    $comment->ticket_id = $ticket->id;
    $comment->user_id = auth()->id();

    if ($request->hasFile('attachment')) {
        $comment->file_path = $request->file('attachment')->store('ticket-attachments', 'public');
    }

    $comment->save();

    $ticket->user->notify(new NewCommentNotification($comment));

    return back();
}
    public function update(Request $request, Comment $comment)
{
    

    $request->validate([
        'body' => 'required|string',
    ]);

    $comment->update([
        'body' => $request->body
    ]);

    return back();
}

public function destroy(Comment $comment)
{
    

    if ($comment->file_path) {
        Storage::disk('public')->delete($comment->file_path);
    }

    $comment->delete();

    return back();
}
}