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
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    


    public function test_user_index_displays_users_view_with_data()
    {
        $user = User::factory()->create();

        $response = $this->get('/drivers'); // adjust route accordingly

        $response->assertStatus(200);
        $response->assertViewIs('driver.index');
        $response->assertViewHas('users', function ($users) use ($user) {
            return $users->contains($user);
        });
    }



}
