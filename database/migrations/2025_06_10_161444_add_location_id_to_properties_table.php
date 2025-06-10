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
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable();
            
            // Add indexes for better query performance
            $table->index('status');
            $table->index('type');
            $table->index('price');
            $table->index('area');
            $table->index('bedrooms');
            $table->index('bathrooms');
            $table->index('location_id');
            $table->index('created_at');
            
            // Composite indexes for common filter combinations
            $table->index(['status', 'type']);
            $table->index(['type', 'price']);
            $table->index(['bedrooms', 'bathrooms']);
            $table->index(['status', 'price']);
            $table->index(['owner_id', 'status']);
            $table->index(['location_id', 'status']);
            
            // Spatial index for location-based queries
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropIndex(['location_id', 'status']);
            $table->dropIndex(['owner_id', 'status']);
            $table->dropIndex(['status', 'price']);
            $table->dropIndex(['bedrooms', 'bathrooms']);
            $table->dropIndex(['type', 'price']);
            $table->dropIndex(['status', 'type']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['location_id']);
            $table->dropIndex(['bathrooms']);
            $table->dropIndex(['bedrooms']);
            $table->dropIndex(['area']);
            $table->dropIndex(['price']);
            $table->dropIndex(['type']);
            $table->dropIndex(['status']);
            
            // Drop column
            $table->dropColumn('location_id');
        });
    }
};
