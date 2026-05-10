<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Reply on Your Ticket')
                    ->line('A new reply has been posted on your ticket.')
                    ->line('Comment: ' . $this->comment->body)
                    ->action('View Ticket', url('/tickets/' . $this->comment->ticket_id))
                    ->line('Thank you for using our support system!');
    }
}
