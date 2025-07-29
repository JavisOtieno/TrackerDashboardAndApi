<?php

namespace Tests\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Location;
use App\Models\Trip;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;

class LocationControllerTest extends TestCase
{
    use WithFaker;

    protected $user;
    protected $trip;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create a user for testing
        $this->user = User::factory()->create();
        
        // Create a trip for testing
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

    /**
     * Test adding location with distance calculation when previous location exists
     */
    public function test_add_location_calculates_distance_from_previous_location()
    {
        Sanctum::actingAs($this->user);

        // Create a previous location
        $previousLocation = Location::factory()->create([
            'user_id' => $this->user->id,
            'lat' => 40.7128,
            'long' => -74.0060,
            'created_at' => Carbon::now()->subMinutes(5)
        ]);

        $locationData = [
            'lat' => 40.7589,
            'long' => -73.9851,
            'type' => 'waypoint',
            'name' => 'Times Square'
        ];

        $response = $this->postJson('/api/addlocation', $locationData);

        $response->assertStatus(200);

        // Check that distance was calculated and stored
        $this->assertDatabaseHas('locations', [
            'lat' => 40.7589,
            'long' => -73.9851,
            'user_id' => $this->user->id,
            'distance' => function ($value) {
                return is_numeric($value) && $value > 0;
            }
        ]);
    }

    /**
     * Test adding first location (no previous location)
     */
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

    /**
     * Test validation errors for addLocation
     */
    public function test_add_location_validation_errors()
    {
        Sanctum::actingAs($this->user);

        // Test missing required fields
        $response = $this->postJson('/api/addlocation', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['lat', 'long', 'type']);

        // Test invalid latitude
        $response = $this->postJson('/api/addlocation', [
            'lat' => 100, // Invalid latitude
            'long' => -74.0060,
            'type' => 'start'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['lat']);

        // Test invalid longitude
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => 200, // Invalid longitude
            'type' => 'start'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['long']);

        // Test invalid trip_id
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'trip_id' => 'invalid'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['trip_id']);
    }

    /**
     * Test unauthorized access to addLocation
     */
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

    /**
     * Test getting today's locations
     */
    public function test_index_returns_todays_locations()
    {
        Sanctum::actingAs($this->user);

        // Create locations for today
        $todayLocations = Location::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::today()
        ]);

        // Create locations for yesterday
        $yesterdayLocations = Location::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::yesterday()
        ]);

        $response = $this->getJson('/api/');

        $response->assertStatus(200)
                ->assertJsonCount(3);

        // Verify only today's locations are returned
        $responseData = $response->json();
        foreach ($responseData as $location) {
            $this->assertEquals(Carbon::today()->toDateString(), 
                Carbon::parse($location['created_at'])->toDateString());
        }
    }

    /**
     * Test getting locations for a specific date
     */
    public function test_get_other_days_locations_returns_locations_for_specific_date()
    {
        Sanctum::actingAs($this->user);

        $targetDate = '2024-01-15';
        
        // Create locations for the target date
        $targetDateLocations = Location::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::parse($targetDate)
        ]);

        // Create locations for a different date
        $otherDateLocations = Location::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::parse('2024-01-16')
        ]);

        $response = $this->getJson("/api/otherdays/{$targetDate}");

        $response->assertStatus(200)
                ->assertJsonCount(3);

        // Verify only locations for the target date are returned
        $responseData = $response->json();
        foreach ($responseData as $location) {
            $this->assertEquals($targetDate, 
                Carbon::parse($location['created_at'])->toDateString());
        }
    }

    /**
     * Test getting current location (latest location)
     */
    public function test_get_current_location_returns_latest_location()
    {
        Sanctum::actingAs($this->user);

        // Create multiple locations
        $oldLocation = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::now()->subHours(2)
        ]);

        $latestLocation = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::now()->subMinutes(5)
        ]);

        $response = $this->getJson('/api/currentlocation');

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $latestLocation->id
                ]);
    }

    /**
     * Test get current location when no locations exist
     */
    public function test_get_current_location_returns_null_when_no_locations()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/currentlocation');

        $response->assertStatus(200)
                ->assertJson(null);
    }

    /**
     * Test authentication required for index endpoint
     */
    public function test_index_requires_authentication()
    {
        $response = $this->getJson('/api/');

        $response->assertStatus(401);
    }

    /**
     * Test authentication required for getOtherDaysLocations endpoint
     */
    public function test_get_other_days_locations_requires_authentication()
    {
        $response = $this->getJson('/api/otherdays/2024-01-15');

        $response->assertStatus(401);
    }

    /**
     * Test authentication required for getCurrentLocation endpoint
     */
    public function test_get_current_location_requires_authentication()
    {
        $response = $this->getJson('/api/currentlocation');

        $response->assertStatus(401);
    }

    /**
     * Test location data structure in responses
     */
    public function test_location_response_has_correct_structure()
    {
        Sanctum::actingAs($this->user);

        $location = Location::factory()->create([
            'user_id' => $this->user->id,
            'trip_id' => $this->trip->id
        ]);

        $response = $this->getJson('/api/');

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

    /**
     * Test adding location with optional fields
     */
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

    /**
     * Test adding location without trip_id
     */
    public function test_add_location_without_trip_id()
    {
        Sanctum::actingAs($this->user);

        $locationData = [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'waypoint',
            'name' => 'Checkpoint'
        ];

        $response = $this->postJson('/api/addlocation', $locationData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('locations', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'waypoint',
            'name' => 'Checkpoint',
            'user_id' => $this->user->id,
            'trip_id' => null
        ]);
    }

    /**
     * Test that locations are ordered by created_at ascending for index
     */
    public function test_index_returns_locations_ordered_by_created_at_ascending()
    {
        Sanctum::actingAs($this->user);

        // Create locations with different timestamps
        $location1 = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::today()->addHours(1)
        ]);

        $location2 = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::today()->addHours(3)
        ]);

        $location3 = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::today()->addHours(2)
        ]);

        $response = $this->getJson('/api/');

        $response->assertStatus(200);
        
        $responseData = $response->json();
        $this->assertEquals($location1->id, $responseData[0]['id']);
        $this->assertEquals($location3->id, $responseData[1]['id']);
        $this->assertEquals($location2->id, $responseData[2]['id']);
    }

    /**
     * Test that other days locations are ordered by created_at descending
     */
    public function test_get_other_days_locations_returns_locations_ordered_by_created_at_descending()
    {
        Sanctum::actingAs($this->user);

        $targetDate = '2024-01-15';

        // Create locations with different timestamps
        $location1 = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::parse($targetDate)->addHours(1)
        ]);

        $location2 = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::parse($targetDate)->addHours(3)
        ]);

        $location3 = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::parse($targetDate)->addHours(2)
        ]);

        $response = $this->getJson("/api/otherdays/{$targetDate}");

        $response->assertStatus(200);
        
        $responseData = $response->json();
        $this->assertEquals($location2->id, $responseData[0]['id']);
        $this->assertEquals($location3->id, $responseData[1]['id']);
        $this->assertEquals($location1->id, $responseData[2]['id']);
    }

    /**
     * Test that getCurrentLocation returns the location with highest ID
     */
    public function test_get_current_location_returns_location_with_highest_id()
    {
        Sanctum::actingAs($this->user);

        // Create locations with different IDs but same timestamp
        $location1 = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::now()
        ]);

        $location2 = Location::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::now()
        ]);

        $response = $this->getJson('/api/currentlocation');

        $response->assertStatus(200)
                ->assertJson([
                    'id' => max($location1->id, $location2->id)
                ]);
    }

    /**
     * Test validation for accuracy field
     */
    public function test_add_location_accuracy_validation()
    {
        Sanctum::actingAs($this->user);

        // Test invalid accuracy (non-numeric)
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'accuracy' => 'invalid'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['accuracy']);
    }

    /**
     * Test validation for distance field
     */
    public function test_add_location_distance_validation()
    {
        Sanctum::actingAs($this->user);

        // Test invalid distance (non-numeric)
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => 'start',
            'distance' => 'invalid'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['distance']);
    }

    /**
     * Test validation for name field length
     */
    public function test_add_location_name_length_validation()
    {
        Sanctum::actingAs($this->user);

        // Test name too long
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

    /**
     * Test validation for type field length
     */
    public function test_add_location_type_length_validation()
    {
        Sanctum::actingAs($this->user);

        // Test type too long
        $longType = str_repeat('a', 256);
        $response = $this->postJson('/api/addlocation', [
            'lat' => 40.7128,
            'long' => -74.0060,
            'type' => $longType
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['type']);
    }
} 