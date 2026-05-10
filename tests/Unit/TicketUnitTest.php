<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_ticket_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

      
        $this->assertInstanceOf(User::class, $ticket->user);
        $this->assertEquals($user->id, $ticket->user->id);
    }
}