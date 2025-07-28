<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;



class UserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
    
    public function test_the_application_returns_a_successful_response(): void
    {
        // $user = \App\Models\User::where('email', 'javisotieno@gmail.com')->first();

        // $this->assertNotNull($user, 'Test user not found in database.');
        $user = \App\Models\User::where('email', 'javisotieno@gmail.com')->first();

        $this->assertNotNull($user, 'Test user not found in database.');
    
        $response = $this->actingAs($user)->get('/');
    
        $response->assertStatus(200);
    }


    public function test_user_index_displays_users_view_with_data()
    {
        // Create an admin user
        $user = \App\Models\User::factory()->create(['usertype' => 'admin']);
        $anotherUser = \App\Models\User::factory()->create(['usertype' => 'admin']);

        $response = $this->actingAs($user)->get('/admins');

        $response->assertStatus(200);
        $response->assertViewIs('admin.index');
        $response->assertViewHas('users', function ($users) use ($anotherUser) {
            return $users->contains($anotherUser);
        });
    }



}
