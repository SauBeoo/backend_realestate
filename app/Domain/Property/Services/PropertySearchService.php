<?php

namespace App\Domain\Property\Services;

use App\Domain\Property\Models\Property;
use App\Domain\Property\Repositories\PropertyRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertySearchService
{
    /**
     * @var PropertyRepositoryInterface
     */
    private $propertyRepository;

    /**
     * PropertySearchService constructor.
     *
     * @param PropertyRepositoryInterface $propertyRepository
     */
    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * Search for properties with advanced filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->propertyRepository->getAllPaginated($filters, $perPage);
    }

    /**
     * Find properties within a radius of a location
     *
     * @param float $latitude
     * @param float $longitude
     * @param float $radiusKm
     * @param array $additionalFilters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findNearby(float $latitude, float $longitude, float $radiusKm, array $additionalFilters = [], int $perPage = 15): LengthAwarePaginator
    {
        // This would normally use a geospatial query with SQL functions
        // For simplicity, we're implementing a basic approach here
        // In a real application, you would use a database spatial extension or a dedicated geospatial service
        
        // Add the location parameters to the filters
        $filters = array_merge($additionalFilters, [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius_km' => $radiusKm
        ]);
        
        // This would require a custom implementation in the repository
        // For now, we'll just use the standard filter function
        return $this->propertyRepository->getAllPaginated($filters, $perPage);
    }

    /**
     * Find similar properties to a given property
     *
     * @param Property $property
     * @param int $limit
     * @return array
     */
    public function findSimilar(Property $property, int $limit = 3): array
    {
        // In a real implementation, this might use more sophisticated similarity algorithms
        // For simplicity, we're just finding properties of the same type in a similar price range
        
        $similarProperties = Property::where('id', '!=', $property->id)
            ->where('type', $property->type)
            ->whereBetween('price', [$property->price * 0.8, $property->price * 1.2])
            ->where('status', $property->status)
            ->limit($limit)
            ->get();
            
        return $similarProperties->toArray();
    }
} 