<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_attributes')) {
            Schema::create('product_attributes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type'); // text, number, select, etc.
                $table->boolean('is_required')->default(false);
                $table->boolean('is_filterable')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('sku')->unique();
                $table->decimal('price', 10, 2);
                $table->integer('stock')->default(0);
                $table->json('attributes'); // Store variant attributes (color, size, etc.)
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('image_url');
                $table->boolean('is_primary')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_reviews')) {
            Schema::create('product_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->integer('rating');
                $table->text('comment')->nullable();
                $table->boolean('is_verified_purchase')->default(false);
                $table->boolean('is_approved')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('promotions')) {
            Schema::create('promotions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->enum('type', ['percentage', 'fixed_amount']);
                $table->decimal('value', 10, 2);
                $table->decimal('minimum_spend', 10, 2)->nullable();
                $table->integer('usage_limit')->nullable();
                $table->integer('usage_count')->default(0);
                $table->dateTime('start_date');
                $table->dateTime('end_date');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_promotions')) {
            Schema::create('product_promotions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['product_id', 'promotion_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_promotions');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_attributes');
    }
}; 