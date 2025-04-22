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
        Schema::create('salestransaction', function (Blueprint $table) {
            $table->id('transactionID')->unsigned();
            $table->unsignedBigInteger('UserID');
            $table->string('PaymentMethod', 50);
            $table->dateTime('TransactionDate');
            $table->decimal('GrandTotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salestransaction');
    }
};
