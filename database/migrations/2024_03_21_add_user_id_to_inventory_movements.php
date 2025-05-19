<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->string('reference_number')->nullable()->after('quantity');
            $table->string('batch_number')->nullable()->after('reference_number');
            $table->decimal('unit_cost', 10, 2)->nullable()->after('batch_number');
            $table->decimal('total_cost', 10, 2)->nullable()->after('unit_cost');
            $table->text('notes')->nullable()->after('total_cost');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'reference_number',
                'batch_number',
                'unit_cost',
                'total_cost',
                'notes'
            ]);
        });
    }
}; 