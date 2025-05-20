<?php

namespace App\Application\Services;

use App\Domain\Property\Models\Property;
use App\Domain\Property\Repositories\PropertyRepositoryInterface;
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
        // Here you could add domain logic, validations, etc.
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
        // Here you could add domain logic, validations, etc.
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
} 