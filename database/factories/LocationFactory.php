<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\User;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'lat' => $this->faker->latitude(),
            'long' => $this->faker->longitude(),
            'trip_id' => null, // or Trip::factory() if you want to create trips
            'user_id' => User::factory(),
            'accuracy' => $this->faker->randomFloat(2, 0, 100),
            'distance' => $this->faker->randomFloat(2, 0, 1000),
            'type' => $this->faker->randomElement(['start', 'movement', 'stopover', 'end']),
            'name' => $this->faker->word(),
        ];
    }
} 