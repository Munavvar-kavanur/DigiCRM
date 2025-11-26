<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/clients', [
            'name' => 'Test Client',
            'email' => 'test@client.com',
            'status' => 'active',
        ]);

        $response->assertRedirect('/clients');
        $this->assertDatabaseHas('clients', [
            'email' => 'test@client.com',
        ]);
    }
}
