<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->string('period', 100);
            $table->decimal('total_revenue', 10, 2);
            $table->decimal('total_profit', 10, 2);
            $table->integer('total_transactions')->unsigned();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_reports');
    }
};
