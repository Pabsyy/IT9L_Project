<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    private static $skuCounter = 0;

    public $productTypes = [
        'Engine Parts' => [
            'Piston' => ['price' => [11200, 44800], 'variants' => ['Standard', 'Performance', 'OEM']],
            'Cylinder Head' => ['price' => [28000, 112000], 'variants' => ['Complete', 'Bare', 'Rebuilt']],
            'Timing Belt Kit' => ['price' => [8400, 22400], 'variants' => ['Basic', 'Premium', 'Complete']],
            'Oil Pump' => ['price' => [5600, 28000], 'variants' => ['Standard', 'High Flow', 'OEM']],
            'Engine Mount' => ['price' => [2800, 16800], 'variants' => ['Standard', 'Heavy Duty', 'Hydraulic']]
        ],
        'Interior' => [
            'Steering Wheel' => ['price' => [16800, 84000], 'variants' => ['Standard', 'Sport', 'Luxury']],
            'Seat' => ['price' => [28000, 112000], 'variants' => ['Driver', 'Passenger', 'Rear']],
            'Dashboard Cover' => ['price' => [2800, 11200], 'variants' => ['Basic', 'Premium', 'Custom Fit']],
            'Floor Mat' => ['price' => [2800, 8400], 'variants' => ['Rubber', 'Carpet', 'All Weather']],
            'Center Console' => ['price' => [5600, 28000], 'variants' => ['Base', 'Premium', 'With Storage']]
        ],
        'Transmission' => [
            'Transmission Kit' => ['price' => [84000, 280000], 'variants' => ['Manual', 'Automatic', 'CVT']],
            'Fluid Pan' => ['price' => [5600, 16800], 'variants' => ['Standard', 'Deep', 'With Filter']],
            'Gear Set' => ['price' => [28000, 112000], 'variants' => ['Standard', 'Performance', 'Heavy Duty']],
            'Clutch Kit' => ['price' => [11200, 44800], 'variants' => ['Standard', 'Heavy Duty', 'Performance']],
            'Transmission Mount' => ['price' => [2800, 11200], 'variants' => ['Standard', 'Heavy Duty', 'Hydraulic']]
        ],
        'Suspension' => [
            'Shock Absorber' => ['price' => [4480, 28000], 'variants' => ['Standard', 'Sport', 'Heavy Duty']],
            'Coilover Kit' => ['price' => [44800, 168000], 'variants' => ['Street', 'Sport', 'Race']],
            'Strut Assembly' => ['price' => [11200, 44800], 'variants' => ['Standard', 'Heavy Duty', 'Sport']],
            'Control Arm' => ['price' => [5600, 28000], 'variants' => ['Front', 'Rear', 'Complete Set']],
            'Sway Bar Link' => ['price' => [1680, 8400], 'variants' => ['Standard', 'Heavy Duty', 'Adjustable']]
        ],
        'Wheel Rim' => [
            'Performance Wheel' => ['price' => [11200, 56000], 'variants' => ['16 inch', '17 inch', '18 inch']],
            'Racing Wheel' => ['price' => [16800, 84000], 'variants' => ['17 inch', '18 inch', '19 inch']],
            'Luxury Wheel' => ['price' => [28000, 112000], 'variants' => ['18 inch', '19 inch', '20 inch']],
            'Standard Wheel' => ['price' => [8400, 28000], 'variants' => ['15 inch', '16 inch', '17 inch']],
            'Wheel Accessory' => ['price' => [1120, 5600], 'variants' => ['Lug Nuts', 'Center Caps', 'Locks']]
        ],
        'Brake Systems' => [
            'Brake Kit' => ['price' => [28000, 140000], 'variants' => ['Standard', 'Sport', 'Racing']],
            'Brake Rotor' => ['price' => [5600, 28000], 'variants' => ['Standard', 'Drilled', 'Slotted']],
            'Brake Pad' => ['price' => [2800, 11200], 'variants' => ['Ceramic', 'Metallic', 'Semi-Metallic']],
            'Brake Caliper' => ['price' => [11200, 56000], 'variants' => ['Front', 'Rear', 'Complete Set']],
            'Brake Line' => ['price' => [1680, 8400], 'variants' => ['Standard', 'Stainless Steel', 'Racing']]
        ]
    ];

    public function definition()
    {
        $category = Category::inRandomOrder()->first();
        $brand = Brand::inRandomOrder()->first();
        $supplier = Supplier::inRandomOrder()->first();
        
        // Get random product type for the category
        $productTypes = $this->productTypes[$category->name] ?? [];
        $productType = array_rand($productTypes);
        $productInfo = $productTypes[$productType];
        
        // Generate product name
        $name = $brand->name . ' ' . $productType;
        
        // Generate SKU: BR-CAT-TYPE-VAR-NUM (e.g., TOY-ENG-PST-STD-001)
        $brandCode = strtoupper(substr($brand->name, 0, 3));
        $categoryCode = strtoupper(substr($category->name, 0, 3));
        $typeCode = strtoupper(substr($productType, 0, 3));
        $variantCode = 'STD'; // Default variant
        self::$skuCounter++;
        $counter = str_pad(self::$skuCounter, 3, '0', STR_PAD_LEFT);
        
        $sku = "{$brandCode}-{$categoryCode}-{$typeCode}-{$variantCode}-{$counter}";
        
        return [
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween($productInfo['price'][0], $productInfo['price'][1]),
            'stock' => $this->faker->numberBetween(30, 100),
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'supplier_id' => $supplier->id,
            'sku' => $sku,
            'featured' => $this->faker->boolean(20),
            'average_cost' => function (array $attributes) {
                return $attributes['price'] * 0.7; // 30% margin
            },
            'last_stocked_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'last_movement_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function featured()
    {
        return $this->state(function (array $attributes) {
            return [
                'featured' => true
            ];
        });
    }

    public function lowStock()
    {
        return $this->state(function (array $attributes) {
            return [
                'stock' => $this->faker->numberBetween(5, 15)
            ];
        });
    }

    public function withVariant($variant)
    {
        return $this->state(function (array $attributes) use ($variant) {
            self::$skuCounter++;
            $counter = str_pad(self::$skuCounter, 3, '0', STR_PAD_LEFT);
            $baseSku = substr($attributes['sku'], 0, strrpos($attributes['sku'], '-'));
            $variantCode = strtoupper(substr(str_replace(' ', '', $variant), 0, 3));
            $sku = "{$baseSku}-{$variantCode}-{$counter}";
            
            return [
                'name' => $attributes['name'] . ' ' . $variant,
                'sku' => $sku,
                'price' => $attributes['price'] * $this->getVariantPriceMultiplier($variant)
            ];
        });
    }

    protected function getVariantPriceMultiplier($variant)
    {
        return match (strtolower($variant)) {
            'performance', 'sport', 'racing', 'luxury' => 1.3,
            'heavy duty', 'premium', 'complete' => 1.2,
            'oem' => 1.1,
            default => 1.0,
        };
    }
} 