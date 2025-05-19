<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition()
    {
        $brands = [
            'Enkei' => 'Japanese wheel manufacturer specializing in lightweight alloy wheels',
            'Mercedes Benz' => 'German luxury automotive manufacturer',
            'Toyota' => 'Japanese automotive manufacturer known for reliability',
            'Bilstein' => 'German manufacturer specializing in high-performance suspension systems',
            'ZF' => 'German manufacturer specializing in transmission and driveline technology',
            'Brembo' => 'Italian manufacturer specializing in high-performance brake systems'
        ];

        $brand = $this->faker->unique()->randomElement(array_keys($brands));
        
        return [
            'name' => $brand,
            'slug' => Str::slug($brand),
            'description' => $brands[$brand]
        ];
    }
} 