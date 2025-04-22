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
        Schema::create('salesreport', function (Blueprint $table) {
            $table->id();
            $table->string('Period', 100);
            $table->decimal('TotalRevenue', 10, 2);
            $table->decimal('TotalProfit', 10, 2);
            $table->integer('TotalTransactions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesreport');
    }
};
