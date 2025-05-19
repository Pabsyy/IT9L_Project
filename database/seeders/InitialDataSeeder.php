<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Create Suppliers
        $localSuppliers = [
            'AutoParts Manila' => 'Toyota',
            'Metro Parts Hub' => 'Enkei',
            'Philippine Auto Solutions' => 'Multiple'
        ];

        $internationalSuppliers = [
            'Brembo International' => 'Brembo',
            'ZF Asia Pacific' => 'ZF',
            'Bilstein Germany' => 'Bilstein'
        ];

        $authorizedDealers = [
            'Mercedes-Benz Authorized Parts' => 'Mercedes Benz',
            'Toyota Genuine Parts' => 'Toyota',
            'Premium Parts Alliance' => 'Multiple'
        ];

        foreach ($localSuppliers as $name => $brand) {
            Supplier::factory()->localDistributor()->create([
                'name' => $name,
                'description' => "Local distributor specializing in {$brand} parts"
            ]);
        }

        foreach ($internationalSuppliers as $name => $brand) {
            Supplier::factory()->internationalManufacturer()->create([
                'name' => $name,
                'description' => "International manufacturer of {$brand} parts"
            ]);
        }

        foreach ($authorizedDealers as $name => $brand) {
            Supplier::factory()->authorizedDealer()->create([
                'name' => $name,
                'description' => "Authorized dealer for {$brand} parts"
            ]);
        }

        // Create Brands
        Brand::factory()->count(6)->create();

        // Create Categories
        Category::factory()->count(6)->create();

        // Create Products with Variants
        $categories = Category::all();
        foreach ($categories as $category) {
            // Create 5 products per category
            for ($i = 0; $i < 5; $i++) {
                // Create base product
                $product = Product::factory()->create();
                
                // Get variants for this product type from the factory
                $productTypes = (new \Database\Factories\ProductFactory($product))->productTypes;
                $variants = $productTypes[$category->name] ?? [];
                
                if (!empty($variants)) {
                    $productType = array_rand($variants);
                    $variantList = $variants[$productType]['variants'] ?? [];
                    
                    // Create variants
                    foreach ($variantList as $variant) {
                        Product::factory()
                            ->withVariant($variant)
                            ->create([
                                'category_id' => $category->id,
                                'brand_id' => $product->brand_id,
                                'supplier_id' => $product->supplier_id
                            ]);
                    }
                }
            }
        }

        // Create some featured products
        Product::factory()->count(10)->featured()->create();

        // Create some low stock products
        Product::factory()->count(5)->lowStock()->create();
    }
} 