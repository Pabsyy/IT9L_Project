<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, let's handle sales movements that have reference numbers (these are customer orders)
        DB::statement("
            UPDATE inventory_movements im
            INNER JOIN sales_transactions st ON im.reference_number = st.reference_number
            SET im.user_id = st.user_id
            WHERE im.type = 'sale' 
            AND im.reference_number IS NOT NULL
            AND im.user_id IS NULL
        ");

        // Then, get the first admin user for other movements
        $adminUser = DB::table('users')
            ->where('role', 'admin')
            ->first();

        if ($adminUser) {
            // Update remaining movements (admin actions) with no user
            DB::table('inventory_movements')
                ->whereNull('user_id')
                ->update([
                    'user_id' => $adminUser->id,
                    'updated_at' => now()
                ]);
        }
    }

    public function down(): void
    {
        // Set user_id back to null for all records
        DB::table('inventory_movements')
            ->update([
                'user_id' => null,
                'updated_at' => now()
            ]);
    }
}; 