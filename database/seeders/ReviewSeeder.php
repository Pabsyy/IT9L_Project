<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesTransaction;
use App\Models\Product;
use App\Models\ProductReview;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    // Review templates by category
    protected $categoryTemplates = [
        'Engine Parts' => [
            5 => [
                'Perfect fit for my {car_model}! Engine performance improved significantly after installation.',
                'Excellent OEM quality part. Installation was straightforward and engine runs smoother now.',
                'Great quality engine component. Noticed immediate improvement in performance.',
            ],
            4 => [
                'Good quality part, slight improvement in engine performance.',
                'Fits well, decent quality for the price.',
                'Installation was a bit tricky but works great.',
            ],
            3 => [
                'Average quality, does the job.',
                'Expected better quality for the price.',
                'Installation instructions could be clearer.',
            ],
            2 => [
                'Had some fitment issues.',
                'Quality is below expectations.',
                'Not the best value for money.',
            ],
            1 => [
                'Poor quality materials.',
                'Doesn\'t match the description.',
                'Would not recommend.',
            ]
        ],
        'Interior' => [
            5 => [
                'Perfect fit and finish! Really upgraded my interior.',
                'High quality materials, easy installation.',
                'Looks and feels premium, great purchase!',
            ],
            4 => [
                'Good quality, minor installation issues.',
                'Nice upgrade to the interior.',
                'Decent quality for the price.',
            ],
            3 => [
                'Average quality materials.',
                'Installation was more difficult than expected.',
                'Looks okay, not great.',
            ],
            2 => [
                'Poor fit and finish.',
                'Materials feel cheap.',
                'Not worth the price.',
            ],
            1 => [
                'Terrible quality.',
                'Complete waste of money.',
                'Doesn\'t fit properly at all.',
            ]
        ],
        'Transmission' => [
            5 => [
                'Smooth shifting after installation!',
                'Perfect OEM replacement part.',
                'Great quality transmission component.',
            ],
            4 => [
                'Good quality, noticeable improvement.',
                'Installation required some expertise.',
                'Works well, a bit pricey.',
            ],
            3 => [
                'Average performance improvement.',
                'Expected better for the price.',
                'Installation was challenging.',
            ],
            2 => [
                'Not the quality I expected.',
                'Difficult to install properly.',
                'Performance is mediocre.',
            ],
            1 => [
                'Poor quality product.',
                'Caused transmission issues.',
                'Avoid this product.',
            ]
        ],
        'Suspension' => [
            5 => [
                'Amazing improvement in handling!',
                'Perfect upgrade for my car.',
                'Professional quality suspension part.',
            ],
            4 => [
                'Good improvement in ride quality.',
                'Installation was straightforward.',
                'Decent upgrade for the price.',
            ],
            3 => [
                'Average improvement.',
                'Expected better performance.',
                'Installation needed professional help.',
            ],
            2 => [
                'Ride quality is worse.',
                'Not the improvement I hoped for.',
                'Overpriced for the quality.',
            ],
            1 => [
                'Made suspension worse.',
                'Poor quality product.',
                'Complete disappointment.',
            ]
        ],
        'Wheel Rim' => [
            5 => [
                'Beautiful design and perfect fitment!',
                'High quality finish, looks amazing.',
                'Great value for money.',
            ],
            4 => [
                'Good looking wheels, minor balance issues.',
                'Nice upgrade from stock.',
                'Decent quality for the price.',
            ],
            3 => [
                'Average quality finish.',
                'Expected better for the price.',
                'Some fitment issues.',
            ],
            2 => [
                'Poor finish quality.',
                'Balance issues.',
                'Not worth the money.',
            ],
            1 => [
                'Terrible quality control.',
                'Damaged during normal use.',
                'Avoid these wheels.',
            ]
        ],
        'Brake Systems' => [
            5 => [
                'Excellent stopping power!',
                'Perfect OEM replacement.',
                'High quality brake components.',
            ],
            4 => [
                'Good improvement in braking.',
                'Installation was straightforward.',
                'Decent upgrade from stock.',
            ],
            3 => [
                'Average performance.',
                'Expected better braking.',
                'Installation needed professional help.',
            ],
            2 => [
                'Braking performance is inconsistent.',
                'Quality is questionable.',
                'Not worth the upgrade.',
            ],
            1 => [
                'Poor braking performance.',
                'Quality control issues.',
                'Unsafe product.',
            ]
        ]
    ];

    protected $carModels = [
        'Toyota Camry',
        'Honda Civic',
        'Ford Mustang',
        'BMW 3 Series',
        'Mercedes C-Class',
        'Audi A4',
        'Volkswagen Golf',
        'Subaru WRX',
        'Nissan 370Z',
        'Mazda 3'
    ];

    public function run(): void
    {
        DB::transaction(function () {
            $products = Product::with('category')->get();
            $users = User::where('role', 'customer')->get();
            $now = now();
            $reviews = [];

            foreach ($products as $product) {
                // Generate 5-15 reviews per product
                $reviewCount = rand(5, 15);
                
                for ($i = 0; $i < $reviewCount; $i++) {
                    $rating = $this->determineRating();
                    $categoryName = $product->category->name;
                    $templates = $this->categoryTemplates[$categoryName][$rating] ?? ['Good product.'];
                    $comment = $templates[array_rand($templates)];
                    
                    $reviews[] = [
                        'product_id' => $product->id,
                        'user_id' => $users->random()->id,
                        'rating' => $rating,
                        'comment' => $comment,
                        'is_verified_purchase' => true,
                        'is_approved' => true,
                        'created_at' => now()->subDays(rand(1, 180)),
                        'updated_at' => $now
                    ];
                }
            }

            // Bulk insert all reviews
            if (!empty($reviews)) {
                $chunkSize = 500; // Process in chunks to avoid memory issues
                foreach (array_chunk($reviews, $chunkSize) as $chunk) {
                    ProductReview::insert($chunk);
                }
            }

            // Update product ratings in a single query
            DB::statement("
                UPDATE products p
                LEFT JOIN (
                    SELECT 
                        product_id,
                        ROUND(AVG(rating), 2) as avg_rating,
                        COUNT(*) as review_count
                    FROM product_reviews
                    GROUP BY product_id
                ) r ON p.id = r.product_id
                SET 
                    p.average_rating = COALESCE(r.avg_rating, 0),
                    p.rating_count = COALESCE(r.review_count, 0)
            ");
        });
    }

    protected function determineRating()
    {
        $rand = rand(1, 100);
        
        if ($rand <= 45) return 5;      // 45%
        if ($rand <= 75) return 4;      // 30%
        if ($rand <= 90) return 3;      // 15%
        if ($rand <= 97) return 2;      // 7%
        return 1;                       // 3%
    }
} 