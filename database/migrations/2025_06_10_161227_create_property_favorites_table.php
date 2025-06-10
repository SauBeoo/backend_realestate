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
        Schema::create('property_favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('property_id');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate favorites
            $table->unique(['user_id', 'property_id'], 'user_property_favorite_unique');
            
            // Indexes
            $table->index('user_id');
            $table->index('property_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_favorites');
    }
};
