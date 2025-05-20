<?php

namespace App\Domain\Property\Enums;

enum PropertyStatus: string
{
    case FOR_SALE = 'for_sale';
    case FOR_RENT = 'for_rent';
    case SOLD = 'sold';
    case RENTED = 'rented';

    /**
     * Get the display name for the enum value.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::FOR_SALE => 'For Sale',
            self::FOR_RENT => 'For Rent',
            self::SOLD => 'Sold',
            self::RENTED => 'Rented',
        };
    }

    /**
     * Get all enum values as an array.
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if the property is available.
     */
    public function isAvailable(): bool
    {
        return in_array($this, [self::FOR_SALE, self::FOR_RENT]);
    }
} 