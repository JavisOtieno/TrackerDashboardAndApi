<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        // Create a user for testing
        $this->user = User::factory()->create();
    }

    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_the_application_returns_a_successful_response()
    {
        // Use the user created in setUp
        $user = $this->user;
        $this->assertNotNull($user, 'Test user not found in database.');

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    public function test_user_index_displays_users_view_with_data()
    {
        $user = $this->user;
        $response = $this->actingAs($user)->get('/users');
        $response->assertStatus(200);
        $response->assertViewIs('user.index');
        $response->assertViewHas('users');
    }
}
