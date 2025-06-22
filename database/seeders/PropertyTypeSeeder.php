<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propertyTypes = [
            [
                'key' => 'apartment',
                'name' => 'Apartment',
                'description' => 'A self-contained housing unit within a larger building',
                'icon' => 'fas fa-building',
                'color' => '#3498db',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'key' => 'house',
                'name' => 'House',
                'description' => 'A standalone residential building',
                'icon' => 'fas fa-home',
                'color' => '#2ecc71',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'key' => 'land',
                'name' => 'Land',
                'description' => 'Vacant land for development or investment',
                'icon' => 'fas fa-map',
                'color' => '#f39c12',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'key' => 'villa',
                'name' => 'Villa',
                'description' => 'A large, luxurious house with extensive grounds',
                'icon' => 'fas fa-crown',
                'color' => '#9b59b6',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($propertyTypes as $typeData) {
            PropertyType::updateOrCreate(
                ['key' => $typeData['key']], // Find by key
                $typeData // Update or create with this data
            );
        }
    }
}
