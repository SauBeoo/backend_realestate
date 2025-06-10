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
        Schema::create('property_amenity_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('property_amenity_id');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate relationships
            $table->unique(['property_id', 'property_amenity_id'], 'property_amenity_unique');
            
            // Indexes
            $table->index('property_id');
            $table->index('property_amenity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_amenity_pivot');
    }
};
