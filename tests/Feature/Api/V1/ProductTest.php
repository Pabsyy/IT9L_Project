<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create(['is_admin' => false]);
    }

    public function test_can_get_products_list()
    {
        $category = Category::factory()->create();
        $products = Product::factory()->count(5)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'category_id',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function test_admin_can_create_product()
    {
        $category = Category::factory()->create();
        $productData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'category_id' => $category->id
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/api/v1/products', $productData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'category_id',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('products', $productData);
    }

    public function test_non_admin_cannot_create_product()
    {
        $category = Category::factory()->create();
        $productData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'category_id' => $category->id
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/products', $productData);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_product()
    {
        $product = Product::factory()->create();
        $updateData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];

        $response = $this->actingAs($this->admin)
            ->putJson("/api/v1/products/{$product->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'category_id',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $updateData['name'],
            'description' => $updateData['description'],
            'price' => $updateData['price'],
        ]);
    }

    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted($product);
    }
} 