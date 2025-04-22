<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id('ProductID'); // This creates an unsignedBigInteger column named 'ProductID'
            $table->unsignedBigInteger('SupplierID')->nullable();
            $table->string('ProductName', 100);
            $table->string('SKU', 50)->unique(); // Add 'SKU' column after 'ProductName'
            $table->text('Description')->nullable();
            $table->string('Image', 255)->nullable();
            $table->decimal('Price', 10, 2);
            $table->integer('Quantity')->default(0);
            $table->string('Category', 100);
            $table->integer('sales')->default(0); // Add 'sales' column with a default value of 0
            $table->timestamps();

            $table->foreign('SupplierID')
                ->references('SupplierID')
                ->on('supplier')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
        Schema::dropIfExists('supplier');
    }
};
