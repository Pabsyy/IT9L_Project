<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get the first admin user
        $adminUser = DB::table('users')
            ->where('role', 'admin')
            ->first();

        if ($adminUser) {
            // Update all existing inventory movements to be associated with the admin user
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