<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Foreign key to users table
            $table->string('customer_name');
            $table->string('customer_email');
            $table->enum('status', ['pending', 'paid', 'shipped', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->timestamps();

            // Update foreign key to cascade on delete
            if (Schema::hasTable('users')) {
                $table->foreign('id')
                    ->references('id')->on('users')
                    ->onDelete('cascade') // Changed from 'restrict' to 'cascade'
                    ->onUpdate('cascade');
            }
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete(); // Updated from 'product'
            $table->integer('quantity')->unsigned();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items'); // Drop order_items first
        Schema::dropIfExists('orders'); // Then drop orders
    }
};
