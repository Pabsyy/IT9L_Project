<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys with proper constraints
            $table->foreignId('category_id')->constrained('categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete()->cascadeOnUpdate();

            // Basic product information
            $table->string('name', 100);
            $table->string('sku', 50)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('average_cost', 10, 2)->nullable();
            
            // Stock management
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('sales')->default(0);
            $table->timestamp('last_stocked_at')->nullable();
            $table->timestamp('last_movement_at')->nullable();
            
            // Product status
            $table->boolean('featured')->default(false);
            
            // Rating system
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            
            // Image management
            $table->string('main_image')->nullable();
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->string('image_4')->nullable();
            
            // Timestamps
            $table->timestamps();

            // Indexes for better performance
            $table->index(['category_id', 'brand_id']);
            $table->index('featured');
            $table->index('stock');
            $table->index('average_rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
