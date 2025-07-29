<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Location;
use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;

class LocationControllerTest extends TestCase
{
    // use RefreshDatabase;
    use WithFaker;

    protected $user;
    protected $trip;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->trip = Trip::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    /**
     * Test adding a new location via API
     */
    public function test_add_location_successfully()
    {
        Sanctum::actingAs($this->user);
        $locationData = [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'name' => 'New York',
            'accuracy' => 10.5,
            'trip_id' => $this->trip->id
        ];
        $response = $this->postJson('/api/addlocation', $locationData);
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Location Added Successfully',
                    'status' => 'success'
                ]);
        $this->assertDatabaseHas('locations', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'user_id' => $this->user->id,
            'type' => 'start',
            'name' => 'New York',
            'accuracy' => 10.5,
            'trip_id' => $this->trip->id
        ]);
    }

    public function test_add_location_calculates_distance_from_previous_location()
    {
        Sanctum::actingAs($this->user);
        $previousLocation = Location::factory()->create([
            'user_id' => $this->user->id,
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'created_at' => Carbon::now()->subMinutes(5)
        ]);
        $locationData = [
            'lat' => 40.7589,
            'long' => -73.9851,
            'type' => 'movement',
            'name' => 'Times Square'
        ];
        $response = $this->postJson('/api/addlocation', $locationData);
        $response->assertStatus(200);
        $location = Location::where('lat', 40.7589)
                           ->where('long', -73.9851)
                           ->where('user_id', $this->user->id)
                           ->first();
        $this->assertNotNull($location);
        $this->assertGreaterThan(0, $location->distance);
    }

    public function test_add_first_location_sets_distance_to_zero()
    {
        Sanctum::actingAs($this->user);
        $locationData = [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'name' => 'Starting Point'
        ];
        $response = $this->postJson('/api/addlocation', $locationData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('locations', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'user_id' => $this->user->id,
            'distance' => 0
        ]);
    }

    public function test_add_location_validation_errors()
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/addlocation', []);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['lat', 'long', 'type']);
        $response = $this->postJson('/api/addlocation', [
            'lat' => 100,
            'long' => -74.0060,
            'type' => 'start'
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['lat']);
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => 200,
            'type' => 'start'
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['long']);
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'trip_id' => 'invalid'
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['trip_id']);
    }

    public function test_add_location_requires_authentication()
    {
        $locationData = [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start'
        ];
        $response = $this->postJson('/api/addlocation', $locationData);
        $response->assertStatus(401);
    }

    public function test_index_returns_todays_locations()
    {
        $this->actingAs($this->user);
        $fixedDate = Carbon::create(2024, 1, 15, 12, 0, 0, 'UTC');
        Location::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'type' => 'movement',
            'created_at' => $fixedDate,
        ]);
        Location::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'type' => 'movement',
            'created_at' => $fixedDate->copy()->subDay(),
        ]);
        $response = $this->get('/api/');
        $response->assertStatus(200)
                ->assertJsonCount(3);
        $responseData = $response->json();
        $today = $fixedDate->toDateString();
        foreach ($responseData as $location) {
            $locationDate = Carbon::parse($location['created_at'])->toDateString();
            $this->assertEquals($today, $locationDate);
        }
    }

    public function test_get_other_days_locations_returns_locations_for_specific_date()
    {
        $this->actingAs($this->user);
        $targetDate = '2024-01-15';
        $fixedDate = Carbon::create(2024, 1, 15, 12, 0, 0, 'UTC');
        Location::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'type' => 'movement',
            'created_at' => $fixedDate,
        ]);
        Location::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'type' => 'movement',
            'created_at' => $fixedDate->copy()->addDay(),
        ]);
        $response = $this->get("/api/otherdays/{$targetDate}");
        $response->assertStatus(200)
                ->assertJsonCount(3);
        $responseData = $response->json();
        foreach ($responseData as $location) {
            $locationDate = Carbon::parse($location['created_at'])->toDateString();
            $this->assertEquals($targetDate, $locationDate);
        }
    }

    public function test_get_current_location_returns_latest_location()
    {
        $this->actingAs($this->user);
        $oldLocation = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'start',
            'created_at' => Carbon::now()->subHours(2)
        ]);
        $latestLocation = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'end',
            'created_at' => Carbon::now()->subMinutes(5)
        ]);
        $response = $this->get('/api/currentlocation');
        $response->assertStatus(200)
                ->assertJson([
                    'id' => $latestLocation->id
                ]);
    }

    public function test_get_current_location_returns_null_when_no_locations()
    {
        $this->actingAs($this->user);
        $response = $this->get('/api/currentlocation');
        $response->assertStatus(200);
        $this->assertNull($response->json());
    }

    public function test_index_requires_authentication()
    {
        $response = $this->get('/api/');
        $response->assertStatus(401);
    }

    public function test_get_other_days_locations_requires_authentication()
    {
        $response = $this->get('/api/otherdays/2024-01-15');
        $response->assertStatus(401);
    }

    public function test_get_current_location_requires_authentication()
    {
        $response = $this->get('/api/currentlocation');
        $response->assertStatus(401);
    }

    public function test_location_response_has_correct_structure()
    {
        $this->actingAs($this->user);
        $location = Location::factory()->create([
            'user_id' => $this->user->id,
            'trip_id' => $this->trip->id,
            'type' => 'movement'
        ]);
        $response = $this->get('/api/');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'lat',
                        'long',
                        'trip_id',
                        'user_id',
                        'accuracy',
                        'distance',
                        'type',
                        'name',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_add_location_with_optional_fields()
    {
        Sanctum::actingAs($this->user);
        $locationData = [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'end',
            'name' => 'Destination',
            'accuracy' => 15.2,
            'trip_id' => $this->trip->id
        ];
        $response = $this->postJson('/api/addlocation', $locationData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('locations', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'end',
            'name' => 'Destination',
            'accuracy' => 15.2,
            'trip_id' => $this->trip->id,
            'user_id' => $this->user->id
        ]);
    }

    public function test_add_location_without_trip_id()
    {
        Sanctum::actingAs($this->user);
        $locationData = [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'movement',
            'name' => 'Checkpoint'
        ];
        $response = $this->postJson('/api/addlocation', $locationData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('locations', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'movement',
            'name' => 'Checkpoint',
            'user_id' => $this->user->id,
            'trip_id' => null
        ]);
    }

    public function test_index_returns_locations_ordered_by_created_at_ascending()
    {
        $this->actingAs($this->user);
        $fixedDate = Carbon::create(2024, 1, 15, 8, 0, 0, 'UTC');
        $location1 = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'start',
            'created_at' => $fixedDate->copy()->addHour()
        ]);
        $location2 = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'end',
            'created_at' => $fixedDate->copy()->addHours(3)
        ]);
        $location3 = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'movement',
            'created_at' => $fixedDate->copy()->addHours(2)
        ]);
        $response = $this->get('/api/');
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals($location1->id, $responseData[0]['id']);
        $this->assertEquals($location3->id, $responseData[1]['id']);
        $this->assertEquals($location2->id, $responseData[2]['id']);
    }

    public function test_get_other_days_locations_returns_locations_ordered_by_created_at_descending()
    {
        $this->actingAs($this->user);
        $targetDate = '2024-01-15';
        $fixedDate = Carbon::create(2024, 1, 15, 8, 0, 0, 'UTC');
        $location1 = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'start',
            'created_at' => $fixedDate->copy()->addHour()
        ]);
        $location2 = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'end',
            'created_at' => $fixedDate->copy()->addHours(3)
        ]);
        $location3 = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'movement',
            'created_at' => $fixedDate->copy()->addHours(2)
        ]);
        $response = $this->get("/api/otherdays/{$targetDate}");
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals($location2->id, $responseData[0]['id']);
        $this->assertEquals($location3->id, $responseData[1]['id']);
        $this->assertEquals($location1->id, $responseData[2]['id']);
    }

    public function test_get_current_location_returns_location_with_highest_id()
    {
        $this->actingAs($this->user);
        $location1 = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'start',
            'created_at' => Carbon::now()
        ]);
        $location2 = Location::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'end',
            'created_at' => Carbon::now()
        ]);
        $response = $this->get('/api/currentlocation');
        $response->assertStatus(200)
                ->assertJson([
                    'id' => max($location1->id, $location2->id)
                ]);
    }

    public function test_add_location_accuracy_validation()
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'accuracy' => 'invalid'
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['accuracy']);
    }

    public function test_add_location_distance_validation()
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'distance' => 'invalid'
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['distance']);
    }

    public function test_add_location_name_length_validation()
    {
        Sanctum::actingAs($this->user);
        $longName = str_repeat('a', 256);
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'name' => $longName
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }

    public function test_add_location_type_enum_validation()
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'invalid_type'
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['type']);
    }

    public function test_add_location_with_all_valid_type_values()
    {
        Sanctum::actingAs($this->user);
        $validTypes = ['start', 'movement', 'stopover', 'end'];
        foreach ($validTypes as $type) {
            $locationData = [
                'lat' => 40.7128,
                'long' => -74.0060,
                'type' => $type,
                'name' => "Location {$type}"
            ];
            $response = $this->postJson('/api/addlocation', $locationData);
            $response->assertStatus(200);
            $this->assertDatabaseHas('locations', [
                'lat' => 40.7128,
                'long' => -74.0060,
                'type' => $type,
                'name' => "Location {$type}",
                'user_id' => $this->user->id
            ]);
        }
    }
} 