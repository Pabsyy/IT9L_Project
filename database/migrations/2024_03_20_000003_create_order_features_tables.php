<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sales Transactions (Orders)
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // Custom order ID format
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('reference_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email')->index();
            $table->string('contact_number');
            $table->text('shipping_address');
            $table->text('billing_address')->nullable();
            $table->enum('delivery_method', ['pickup', 'delivery'])->default('pickup')->index();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->enum('order_status', ['pending', 'processing', 'paid', 'shipped', 'delivered', 'cancelled'])
                  ->default('pending')
                  ->index();
            $table->text('notes')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for common queries
            $table->index(['order_status', 'created_at']);
            $table->index(['user_id', 'order_status']);
            $table->index(['created_at', 'order_status']);
        });

        // Sales Transaction Items (Order Items)
        Schema::create('sales_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->integer('quantity')->unsigned();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->string('sku')->nullable();
            $table->json('product_snapshot')->nullable(); // Store product details at time of purchase
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['sales_transaction_id', 'product_id']);
            $table->index('created_at');
        });

        // Order Status History
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status');
            $table->text('comment')->nullable();
            $table->boolean('notify_customer')->default(false);
            $table->timestamps();
        });

        // Order Returns
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->string('reason');
            $table->text('description')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('return_label')->nullable();
            $table->timestamps();
        });

        // Return Items
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('order_returns')->cascadeOnDelete();
            $table->foreignId('sales_transaction_item_id')->constrained('sales_transaction_items')->cascadeOnDelete();
            $table->integer('quantity');
            $table->string('condition');
            $table->timestamps();
        });

        // Shipping Methods
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('base_cost', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Shipping Zones
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('regions'); // Store country/state/zip codes
            $table->timestamps();
        });

        // Shipping Zone Rates
        Schema::create('shipping_zone_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_zone_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_method_id')->constrained()->cascadeOnDelete();
            $table->decimal('cost', 10, 2);
            $table->decimal('minimum_order_amount', 10, 2)->nullable();
            $table->decimal('maximum_order_amount', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['shipping_zone_id', 'shipping_method_id']);
        });

        // Payment Transactions
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_transaction_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id');
            $table->string('payment_method');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']);
            $table->json('payment_details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('shipping_zone_rates');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('order_returns');
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('sales_transaction_items');
        Schema::dropIfExists('sales_transactions');
    }
}; 