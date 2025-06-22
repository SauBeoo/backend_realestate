<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PropertyStatus;

class PropertyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propertyStatuses = [
            [
                'key' => 'for_sale',
                'name' => 'For Sale',
                'description' => 'Property is available for purchase',
                'badge_class' => 'bg-success',
                'color' => '#28a745',
                'is_available' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'key' => 'for_rent',
                'name' => 'For Rent',
                'description' => 'Property is available for rental',
                'badge_class' => 'bg-info',
                'color' => '#17a2b8',
                'is_available' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'key' => 'sold',
                'name' => 'Sold',
                'description' => 'Property has been sold',
                'badge_class' => 'bg-danger',
                'color' => '#dc3545',
                'is_available' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'key' => 'rented',
                'name' => 'Rented',
                'description' => 'Property has been rented out',
                'badge_class' => 'bg-warning',
                'color' => '#ffc107',
                'is_available' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($propertyStatuses as $statusData) {
            PropertyStatus::updateOrCreate(
                ['key' => $statusData['key']], // Find by key
                $statusData // Update or create with this data
            );
        }
    }
}
