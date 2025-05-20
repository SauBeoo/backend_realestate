<?php

namespace App\Domain\Property\Enums;

enum PropertyType: string
{
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case LAND = 'land';
    case VILLA = 'villa';

    /**
     * Get the display name for the enum value.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::APARTMENT => 'Apartment',
            self::HOUSE => 'House',
            self::LAND => 'Land',
            self::VILLA => 'Villa',
        };
    }

    /**
     * Get all enum values as an array.
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
} 