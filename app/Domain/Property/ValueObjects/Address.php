<?php

namespace App\Domain\Property\ValueObjects;

class Address
{
    /**
     * @param string $street
     * @param string $city
     * @param string $state
     * @param string $country
     * @param string $zipCode
     * @param float|null $latitude
     * @param float|null $longitude
     */
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly string $state,
        public readonly string $country,
        public readonly string $zipCode,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
    ) {
    }

    /**
     * Create an Address from a string representation.
     */
    public static function fromString(string $addressString): self
    {
        // This is a simplified example - in a real implementation,
        // you might use an address parsing service
        $parts = explode(', ', $addressString);
        
        return new self(
            street: $parts[0] ?? '',
            city: $parts[1] ?? '',
            state: $parts[2] ?? '',
            country: $parts[3] ?? '',
            zipCode: $parts[4] ?? '',
        );
    }

    /**
     * Get the full string representation of the address.
     */
    public function toString(): string
    {
        return "{$this->street}, {$this->city}, {$this->state}, {$this->country}, {$this->zipCode}";
    }
} 