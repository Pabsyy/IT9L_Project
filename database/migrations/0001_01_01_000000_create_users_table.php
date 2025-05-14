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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Use default 'id' as primary key
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->rememberToken();
            $table->timestamps(0);
        });

        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sales_transactions')) {
            Schema::table('sales_transactions', function (Blueprint $table) {
                try {
                    $table->dropForeign('sales_transactions_user_id_foreign');
                } catch (\Exception $e) {
                    // The foreign key does not exist
                }
            });
        }
        Schema::dropIfExists('users');
    }
};
