<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketFeatureTest extends TestCase
{
    use RefreshDatabase; 

    /** @test */
    public function guests_cannot_access_ticket_system()
    {
      
        $response = $this->get('/tickets');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_create_a_ticket()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tickets', [
            'subject' => 'Technical Issue',
            'description' => 'Cannot access the database.',
            'category' => 'Technical',
            'priority' => 'High',
        ]);

        $this->assertDatabaseHas('tickets', ['subject' => 'Technical Issue']);
        $response->assertRedirect(route('tickets.index'));
    }

    /** @test */
    public function users_cannot_view_others_tickets()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        $ticket = Ticket::factory()->create(['user_id' => $user1->id]);

        
        $response = $this->actingAs($user2)->get("/tickets/{$ticket->id}");
        $response->assertStatus(403);
    }
}