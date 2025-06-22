<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PropertyType;
use App\Models\PropertyStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new foreign key columns
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('property_type_id')->nullable()->after('type');
            $table->unsignedBigInteger('property_status_id')->nullable()->after('status');
        
        });

        // Migrate data from string columns to foreign keys
        $this->migrateData();

        // Remove old string columns
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back string columns
        Schema::table('properties', function (Blueprint $table) {
            $table->string('type')->nullable()->after('property_type_id');
            $table->string('status')->nullable()->after('property_status_id');
        });

        // Migrate data back to string columns
        $this->migrateDataBack();

        // Remove foreign key columns
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['property_type_id']);
            $table->dropForeign(['property_status_id']);
            $table->dropColumn(['property_type_id', 'property_status_id']);
        });
    }

    /**
     * Migrate data from string to foreign keys
     */
    private function migrateData(): void
    {
        $properties = \DB::table('properties')->get();
        
        foreach ($properties as $property) {
            $updates = [];
            
            // Find property type
            if ($property->type) {
                $propertyType = PropertyType::where('key', $property->type)->first();
                if ($propertyType) {
                    $updates['property_type_id'] = $propertyType->id;
                }
            }
            
            // Find property status
            if ($property->status) {
                $propertyStatus = PropertyStatus::where('key', $property->status)->first();
                if ($propertyStatus) {
                    $updates['property_status_id'] = $propertyStatus->id;
                }
            }
            
            if (!empty($updates)) {
                \DB::table('properties')->where('id', $property->id)->update($updates);
            }
        }
    }

    /**
     * Migrate data back from foreign keys to string
     */
    private function migrateDataBack(): void
    {
        $properties = \DB::table('properties')
            ->leftJoin('property_types', 'properties.property_type_id', '=', 'property_types.id')
            ->leftJoin('property_statuses', 'properties.property_status_id', '=', 'property_statuses.id')
            ->select('properties.id', 'property_types.key as type_key', 'property_statuses.key as status_key')
            ->get();
        
        foreach ($properties as $property) {
            $updates = [];
            
            if ($property->type_key) {
                $updates['type'] = $property->type_key;
            }
            
            if ($property->status_key) {
                $updates['status'] = $property->status_key;
            }
            
            if (!empty($updates)) {
                \DB::table('properties')->where('id', $property->id)->update($updates);
            }
        }
    }
};
