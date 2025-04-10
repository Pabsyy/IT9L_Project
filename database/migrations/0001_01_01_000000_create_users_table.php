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
            $table->id('UserID')->unsigned();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('username', 101)->storedAs("CONCAT(first_name, ' ', last_name)"); // Ensure MariaDB version supports this
            $table->string('email', 100)->unique();
            $table->string('profile_picture_url')->nullable(); // Removed 'after' clause
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->rememberToken();
            $table->enum('role', ['admin', 'customer'])->default('admin');
            $table->string('address', 255)->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->timestamps(0);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartitem');
        Schema::dropIfExists('purchaseorderitem');
        Schema::dropIfExists('cart');
        Schema::dropIfExists('purchaseorder');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('product');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
