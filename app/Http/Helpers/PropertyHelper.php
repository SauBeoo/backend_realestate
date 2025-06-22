<?php

namespace App\Http\Helpers;

use App\Models\PropertyType;
use App\Models\PropertyStatus;

class PropertyHelper
{
    /**
     * Get status badge class for property status
     */
    public static function getStatusBadgeClass(string $status): string
    {
        $propertyStatus = PropertyStatus::getByKey($status);
        return $propertyStatus?->badge_class ?? 'bg-secondary';
    }

    /**
     * Get property type icon class
     */
    public static function getTypeIcon(string $type): string
    {
        $propertyType = PropertyType::getByKey($type);
        return $propertyType?->icon ?? 'fas fa-home';
    }

    /**
     * Format property price
     */
    public static function formatPrice(float $price, string $currency = '$'): string
    {
        if ($price >= 1000000) {
            return $currency . number_format($price / 1000000, 1) . 'M';
        } elseif ($price >= 1000) {
            return $currency . number_format($price / 1000, 0) . 'K';
        }
        
        return $currency . number_format($price, 0);
    }

    /**
     * Get property size display
     */
    public static function formatSize(?float $size, string $unit = 'sqft'): string
    {
        if (!$size) {
            return 'N/A';
        }

        return number_format($size, 0) . ' ' . $unit;
    }

    /**
     * Get available property types for forms
     */
    public static function getPropertyTypes(): array
    {
        return PropertyType::getKeyOptionsArray();
    }

    /**
     * Get available property statuses for forms
     */
    public static function getPropertyStatuses(): array
    {
        return PropertyStatus::getKeyOptionsArray();
    }

    /**
     * Get property types with IDs for forms
     */
    public static function getPropertyTypesWithIds(): array
    {
        return PropertyType::getOptionsArray();
    }

    /**
     * Get property statuses with IDs for forms
     */
    public static function getPropertyStatusesWithIds(): array
    {
        return PropertyStatus::getOptionsArray();
    }

    /**
     * Generate property search suggestions
     */
    public static function getSearchSuggestions(string $query = ''): array
    {
        $suggestions = [
            'types' => self::getPropertyTypes(),
            'statuses' => self::getPropertyStatuses(),
            'priceRanges' => [
                '0-100000' => 'Under $100K',
                '100000-500000' => '$100K - $500K',
                '500000-1000000' => '$500K - $1M',
                '1000000-' => 'Above $1M',
            ],
            'features' => [
                'Swimming Pool',
                'Garage',
                'Garden',
                'Balcony',
                'Fireplace',
                'Air Conditioning',
                'Central Heating',
                'Security System',
            ]
        ];

        if (!empty($query)) {
            $query = strtolower($query);
            foreach ($suggestions as $key => &$items) {
                if (is_array($items)) {
                    $items = array_filter($items, function($item) use ($query) {
                        return strpos(strtolower($item), $query) !== false;
                    });
                }
            }
        }

        return $suggestions;
    }

    /**
     * Validate property filters
     */
    public static function validateFilters(array $filters): array
    {
        $validatedFilters = [];

        // Validate search query
        if (!empty($filters['search'])) {
            $validatedFilters['search'] = trim($filters['search']);
        }

        // Validate type
        if (!empty($filters['type'])) {
            $validTypes = PropertyType::active()->pluck('key')->toArray();
            if (in_array($filters['type'], $validTypes)) {
                $validatedFilters['type'] = $filters['type'];
            }
        }

        // Validate status
        if (!empty($filters['status'])) {
            $validStatuses = PropertyStatus::active()->pluck('key')->toArray();
            if (in_array($filters['status'], $validStatuses)) {
                $validatedFilters['status'] = $filters['status'];
            }
        }

        // Validate property_type_id
        if (!empty($filters['property_type_id']) && is_numeric($filters['property_type_id'])) {
            $validatedFilters['property_type_id'] = (int) $filters['property_type_id'];
        }

        // Validate property_status_id
        if (!empty($filters['property_status_id']) && is_numeric($filters['property_status_id'])) {
            $validatedFilters['property_status_id'] = (int) $filters['property_status_id'];
        }

        // Validate price range
        if (!empty($filters['min_price']) && is_numeric($filters['min_price'])) {
            $validatedFilters['min_price'] = (float) $filters['min_price'];
        }

        if (!empty($filters['max_price']) && is_numeric($filters['max_price'])) {
            $validatedFilters['max_price'] = (float) $filters['max_price'];
        }

        // Validate bedrooms
        if (!empty($filters['bedrooms']) && is_numeric($filters['bedrooms'])) {
            $validatedFilters['bedrooms'] = (int) $filters['bedrooms'];
        }

        return $validatedFilters;
    }

    /**
     * Build breadcrumb for property pages
     */
    public static function buildBreadcrumb(string $currentPage, ?object $property = null): array
    {
        $breadcrumb = [
            ['title' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['title' => 'Properties', 'url' => route('admin.properties.index')],
        ];

        switch ($currentPage) {
            case 'create':
                $breadcrumb[] = ['title' => 'Create Property', 'url' => null];
                break;
            case 'edit':
                if ($property) {
                    $breadcrumb[] = ['title' => 'Edit: ' . $property->title, 'url' => null];
                }
                break;
            case 'show':
                if ($property) {
                    $breadcrumb[] = ['title' => $property->title, 'url' => null];
                }
                break;
        }

        return $breadcrumb;
    }

    /**
     * Get property type by ID
     */
    public static function getPropertyTypeById(int $id): ?PropertyType
    {
        return PropertyType::find($id);
    }

    /**
     * Get property status by ID
     */
    public static function getPropertyStatusById(int $id): ?PropertyStatus
    {
        return PropertyStatus::find($id);
    }

    /**
     * Get available property statuses (for sale, for rent)
     */
    public static function getAvailableStatuses(): array
    {
        return PropertyStatus::getAvailableOptionsArray();
    }
} 