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
            $table->id();
            
            // Basic Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            // Profile Information
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->string('occupation')->nullable();
            $table->string('company')->nullable();
            
            // Address Information
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Vietnam');
            
            // User Type & Status
            $table->enum('user_type', ['buyer', 'seller', 'agent', 'admin'])->default('buyer');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->boolean('is_verified')->default(false);
            
            // Agent/Seller specific fields
            $table->string('license_number')->nullable(); // For agents
            $table->string('agency_name')->nullable(); // For agents
            $table->decimal('commission_rate', 5, 2)->nullable(); // For agents
            
            // Preferences
            $table->json('preferences')->nullable(); // JSON for user preferences
            $table->boolean('receive_notifications')->default(true);
            $table->boolean('receive_marketing')->default(false);
            
            // Security & Login
            $table->string('remember_token')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
            
            // Indexes
            $table->index('email');
            $table->index('phone');
            $table->index('user_type');
            $table->index('status');
            $table->index('is_verified');
            $table->index('city');
            $table->index('last_login_at');
            $table->index(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
