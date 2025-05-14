<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('name', 100);
            $table->string('sku', 50)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->unsigned()->default(0);  // Current available stock
            $table->integer('sales')->unsigned()->default(0);  // Total units sold
            $table->string('category', 100)->nullable();
            $table->string('brand', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
