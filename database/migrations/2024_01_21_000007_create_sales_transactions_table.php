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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->decimal('grand_total', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'transfer']);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('sales_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->foreign('transaction_id')->references('order_id')->on('sales_transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {       
        Schema::table('sales_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('sales_transactions');
        Schema::dropIfExists('sales_transaction_items');
        Schema::table('sales_transaction_items', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
        });
 
    }
};
