<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'contact_person' => $this->faker->name,
            'address' => $this->faker->address,
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'status' => 'active',
            'rating' => $this->faker->randomFloat(1, 4.0, 5.0),
            'on_time_delivery_rate' => $this->faker->randomFloat(2, 85, 98),
            'response_time' => $this->faker->randomFloat(1, 1, 24), // hours
        ];
    }

    public function localDistributor()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Local: ' . $attributes['name'],
                'rating' => $this->faker->randomFloat(1, 4.0, 5.0),
                'on_time_delivery_rate' => $this->faker->randomFloat(2, 85, 95),
            ];
        });
    }

    public function internationalManufacturer()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Int: ' . $attributes['name'],
                'rating' => $this->faker->randomFloat(1, 4.5, 5.0),
                'on_time_delivery_rate' => $this->faker->randomFloat(2, 90, 98),
            ];
        });
    }

    public function authorizedDealer()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Auth: ' . $attributes['name'],
                'rating' => $this->faker->randomFloat(1, 4.5, 5.0),
                'on_time_delivery_rate' => $this->faker->randomFloat(2, 90, 98),
            ];
        });
    }
} 