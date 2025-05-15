<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->string('order_id')->primary();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->name('fk_sales_transactions_user');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->decimal('grand_total', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'transfer']);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('Transaction_date')->useCurrent();
            $table->timestamps();
        });

        Schema::create('sales_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->foreign('transaction_id', 'fk_sales_items_transaction')
                ->references('order_id')
                ->on('sales_transactions')
                ->onDelete('cascade');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('restrict')
                ->name('fk_sales_items_product');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('sales_transaction_items', function (Blueprint $table) {
            if (Schema::hasColumn('sales_transaction_items', 'transaction_id')) {
                try {
                    $table->dropForeign('fk_sales_items_transaction');
                } catch (\Exception $e) {
                    // Foreign key may not exist, ignore the error
                }
            }
            $table->dropForeign('fk_sales_items_product');
        });

        Schema::table('sales_transactions', function (Blueprint $table) {
            $table->dropForeign('fk_sales_transactions_user');
        });

        Schema::dropIfExists('sales_transaction_items');
        Schema::dropIfExists('sales_transactions');
    }
};
