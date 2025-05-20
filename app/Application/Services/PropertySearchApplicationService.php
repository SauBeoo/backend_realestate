<?php

namespace App\Application\Services;

use App\Domain\Property\Models\Property;
use App\Domain\Property\Services\PropertySearchService;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertySearchApplicationService
{
    /**
     * @var PropertySearchService
     */
    private $propertySearchService;

    /**
     * @var PropertyService
     */
    private $propertyService;

    /**
     * PropertySearchApplicationService constructor.
     *
     * @param PropertySearchService $propertySearchService
     * @param PropertyService $propertyService
     */
    public function __construct(
        PropertySearchService $propertySearchService,
        PropertyService $propertyService
    ) {
        $this->propertySearchService = $propertySearchService;
        $this->propertyService = $propertyService;
    }

    /**
     * Search for properties with filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchProperties(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->propertySearchService->search($filters, $perPage);
    }

    /**
     * Find properties near a location
     *
     * @param float $latitude
     * @param float $longitude
     * @param float $radiusKm
     * @param array $additionalFilters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findPropertiesNearby(
        float $latitude,
        float $longitude,
        float $radiusKm = 5.0,
        array $additionalFilters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->propertySearchService->findNearby(
            $latitude,
            $longitude,
            $radiusKm,
            $additionalFilters,
            $perPage
        );
    }

    /**
     * Get similar properties to a given property
     *
     * @param int $propertyId
     * @param int $limit
     * @return array
     */
    public function getSimilarProperties(int $propertyId, int $limit = 3): array
    {
        $property = $this->propertyService->getPropertyById($propertyId);
        
        if (!$property) {
            return [];
        }
        
        return $this->propertySearchService->findSimilar($property, $limit);
    }
} 