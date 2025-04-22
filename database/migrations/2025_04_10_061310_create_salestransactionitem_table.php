<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salestransactionitem', function (Blueprint $table) {
            $table->id('transactionitemID')->unsigned();
            $table->unsignedBigInteger('TransactionID');
            $table->unsignedBigInteger('ProductID');
            $table->integer('Quantity');
            $table->decimal('UnitPrice', 10, 2);
            $table->timestamps();

            $table->foreign('TransactionID')->references('TransactionID')->on('salestransaction')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ProductID')->references('ProductID')->on('product')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salestransactionitem');
    }
};
