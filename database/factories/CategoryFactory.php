<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        $categories = [
            'Engine Parts' => 'Essential components for automotive engines',
            'Interior' => 'Vehicle interior parts and accessories',
            'Transmission' => 'Transmission and drivetrain components',
            'Suspension' => 'Suspension and steering components',
            'Wheel Rim' => 'Automotive wheels and related components',
            'Brake Systems' => 'Brake components and systems'
        ];

        $category = $this->faker->unique()->randomElement(array_keys($categories));
        
        return [
            'name' => $category,
            'slug' => Str::slug($category),
            'description' => $categories[$category]
        ];
    }
} 