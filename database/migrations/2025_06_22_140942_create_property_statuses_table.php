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
        Schema::create('property_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // for_sale, for_rent, sold, rented
            $table->string('name'); // For Sale, For Rent, Sold, Rented
            $table->string('description')->nullable();
            $table->string('badge_class')->nullable(); // CSS class for badge
            $table->string('color')->nullable(); // Hex color code
            $table->boolean('is_available')->default(false); // true for for_sale, for_rent
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_statuses');
    }
};
