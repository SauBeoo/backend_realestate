<?php

namespace App\Application\Services;

use App\Domain\Property\Models\Property;
use App\Domain\Property\Repositories\PropertyRepositoryInterface;
use App\Models\PropertyType;
use App\Models\PropertyStatus;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertyService
{
    /**
     * @var PropertyRepositoryInterface
     */
    protected $propertyRepository;

    /**
     * PropertyService constructor.
     *
     * @param PropertyRepositoryInterface $propertyRepository
     */
    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * Get all properties with pagination and filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllProperties(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->propertyRepository->getAllPaginated($filters, $perPage);
    }

    /**
     * Get a property by ID
     *
     * @param int $id
     * @return Property|null
     */
    public function getPropertyById(int $id): ?Property
    {
        return $this->propertyRepository->findById($id);
    }

    /**
     * Create a new property
     *
     * @param array $data
     * @return Property
     */
    public function createProperty(array $data): Property
    {
        // Process features if it's a string
        if (isset($data['features_text']) && is_string($data['features_text'])) {
            $data['features'] = array_map('trim', explode(',', $data['features_text']));
            unset($data['features_text']);
        }

        // Convert type and status to IDs
        $data = $this->convertTypeAndStatusToIds($data);

        return $this->propertyRepository->create($data);
    }

    /**
     * Update a property
     *
     * @param int $id
     * @param array $data
     * @return Property|null
     */
    public function updateProperty(int $id, array $data): ?Property
    {
        // Process features if it's a string
        if (isset($data['features_text']) && is_string($data['features_text'])) {
            $data['features'] = array_map('trim', explode(',', $data['features_text']));
            unset($data['features_text']);
        }

        // Convert type and status to IDs
        $data = $this->convertTypeAndStatusToIds($data);

        return $this->propertyRepository->update($id, $data);
    }

    /**
     * Delete a property
     *
     * @param int $id
     * @return bool
     */
    public function deleteProperty(int $id): bool
    {
        return $this->propertyRepository->delete($id);
    }

    /**
     * Get properties by type
     *
     * @param string $type
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPropertiesByType(string $type, int $perPage = 15): LengthAwarePaginator
    {
        return $this->propertyRepository->getByType($type, $perPage);
    }

    /**
     * Get properties by owner
     *
     * @param int $ownerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPropertiesByOwner(int $ownerId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->propertyRepository->getByOwnerId($ownerId, $perPage);
    }

    /**
     * Get property statistics
     */
    public function getStatistics(): array
    {
        return $this->propertyRepository->getStatistics();
    }

    /**
     * Search properties with advanced criteria
     */
    public function searchProperties(array $criteria, int $perPage = 15): LengthAwarePaginator
    {
        return $this->propertyRepository->search($criteria, $perPage);
    }

    /**
     * Convert type and status strings to their corresponding IDs
     *
     * @param array $data
     * @return array
     */
    protected function convertTypeAndStatusToIds(array $data): array
    {
        // Convert type string to property_type_id
        if (isset($data['type'])) {
            $propertyType = PropertyType::where('key', $data['type'])->first();
            if ($propertyType) {
                $data['property_type_id'] = $propertyType->id;
            }
            unset($data['type']);
        }

        // Convert status string to property_status_id
        if (isset($data['status'])) {
            $propertyStatus = PropertyStatus::where('key', $data['status'])->first();
            if ($propertyStatus) {
                $data['property_status_id'] = $propertyStatus->id;
            }
            unset($data['status']);
        }

        return $data;
    }
} 