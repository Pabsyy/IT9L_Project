<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, convert the column to a string temporarily
        DB::statement('ALTER TABLE inventory_movements MODIFY COLUMN type VARCHAR(20)');
        
        // Then, update it to the new enum with all values
        DB::statement("ALTER TABLE inventory_movements MODIFY COLUMN type ENUM('purchase', 'sale', 'adjustment', 'damage', 'return') NOT NULL");
    }

    public function down(): void
    {
        // Convert back to original enum values
        DB::statement("ALTER TABLE inventory_movements MODIFY COLUMN type ENUM('purchase', 'sale', 'adjustment') NOT NULL");
    }
}; 