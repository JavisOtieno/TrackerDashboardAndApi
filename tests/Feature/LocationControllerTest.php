<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;

class LocationControllerTest extends TestCase
{
    // use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        // Create a user and locations for testing
        $this->user = User::factory()->create();
        $this->locations = Location::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::today(),
        ]);
    }

    public function test_index_displays_map_with_locations_and_total_distance()
    {
        $response = $this->actingAs($this->user)->get('/livetrail');
        $response->assertStatus(200);
        $response->assertViewIs('map');
        $response->assertViewHas(['locations', 'totalDistance']);
    }

    public function test_location_index_displays_locations_view()
    {
        $response = $this->actingAs($this->user)->get('/locations');
        $response->assertStatus(200);
        $response->assertViewIs('location.index');
        $response->assertViewHas('locations');
    }

    public function test_get_other_days_locations_returns_json()
    {
        $date = Carbon::today()->toDateString();
        $response = $this->actingAs($this->user)->getJson("/otherdays/{$date}/{$this->user->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'lat', 'long', 'user_id', 'created_at', 'updated_at', 'deleted_at']
        ]);
    }

    public function test_other_days_trail_displays_mapotherdays_view()
    {
        $response = $this->actingAs($this->user)->get('/dailytrails');
        $response->assertStatus(200);
        $response->assertViewIs('mapotherdays');
        $response->assertViewHas(['locations', 'drivers']);
    }

    public function test_show_current_location_displays_currentlocation_view()
    {
        $response = $this->actingAs($this->user)->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('currentlocation');
        $response->assertViewHas(['location', 'userswithcurrentlocations', 'drivers']);
    }

    public function test_get_current_locations_returns_json_for_all_drivers()
    {
        $response = $this->actingAs($this->user)->getJson('/currentlocations/0');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'latest_location']
        ]);
    }

    public function test_get_current_locations_returns_json_for_specific_driver()
    {
        $response = $this->actingAs($this->user)->getJson("/currentlocations/{$this->user->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'latest_location']
        ]);
    }
} 