<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Trip::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate realistic coordinates for start and end locations
        $startLat = $this->faker->latitude(40.7, 40.8); // New York area
        $startLong = $this->faker->longitude(-74.0, -73.9);
        $endLat = $this->faker->latitude(40.7, 40.8);
        $endLong = $this->faker->longitude(-74.0, -73.9);

        return [
            'start_location' => $this->faker->address(),
            'start_lat' => (string) $startLat,
            'start_long' => (string) $startLong,
            'end_location' => $this->faker->address(),
            'end_lat' => (string) $endLat,
            'end_long' => (string) $endLong,
            'description' => $this->faker->paragraph(3),
            'amount' => $this->faker->numberBetween(1000, 50000), // Amount in cents or smallest currency unit
            'distance' => $this->faker->randomFloat(2, 1.0, 50.0), // Distance in km
            'status' => $this->faker->randomElement(['pending', 'active', 'completed', 'cancelled']),
            'user_id' => User::factory(), // Create a user if not provided
            'customer_id' => User::factory(), // Create a customer if not provided
        ];
    }

    /**
     * Indicate that the trip is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the trip is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the trip is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the trip is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Set a specific user for the trip.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Set a specific customer for the trip.
     */
    public function forCustomer(User $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
        ]);
    }

    /**
     * Create a trip with specific coordinates.
     */
    public function withCoordinates(float $startLat, float $startLong, float $endLat, float $endLong): static
    {
        return $this->state(fn (array $attributes) => [
            'start_lat' => (string) $startLat,
            'start_long' => (string) $startLong,
            'end_lat' => (string) $endLat,
            'end_long' => (string) $endLong,
        ]);
    }

    /**
     * Create a trip with specific locations.
     */
    public function withLocations(string $startLocation, string $endLocation): static
    {
        return $this->state(fn (array $attributes) => [
            'start_location' => $startLocation,
            'end_location' => $endLocation,
        ]);
    }

    /**
     * Create a trip with a specific amount.
     */
    public function withAmount(int $amount): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount,
        ]);
    }

    /**
     * Create a trip with a specific distance.
     */
    public function withDistance(float $distance): static
    {
        return $this->state(fn (array $attributes) => [
            'distance' => $distance,
        ]);
    }

    /**
     * Create a trip without end coordinates (nullable fields).
     */
    public function withoutEndCoordinates(): static
    {
        return $this->state(fn (array $attributes) => [
            'end_lat' => null,
            'end_long' => null,
        ]);
    }

    /**
     * Create a trip without a customer.
     */
    public function withoutCustomer(): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => null,
        ]);
    }

    /**
     * Create a trip without a user.
     */
    public function withoutUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    /**
     * Create a trip with a short description.
     */
    public function withShortDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => $this->faker->sentence(),
        ]);
    }

    /**
     * Create a trip with a long description.
     */
    public function withLongDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => $this->faker->paragraphs(5, true),
        ]);
    }
} 