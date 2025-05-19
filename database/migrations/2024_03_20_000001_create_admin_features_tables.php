<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Admin Activity Logs
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action');
            $table->string('module');
            $table->text('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        // Admin Roles
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Admin Permissions
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('module');
            $table->timestamps();
        });

        // Role-Permission Relationship
        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('admin_roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('admin_permissions')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
        });

        // User-Role Relationship
        Schema::create('admin_user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('admin_roles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
        });

        // Admin Settings
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_user_roles');
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('admin_settings');
    }
}; 