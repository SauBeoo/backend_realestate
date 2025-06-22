<?php

namespace App\Domain\Property\Repositories;

use App\Domain\Property\Models\Property;
use Illuminate\Pagination\LengthAwarePaginator;

interface PropertyRepositoryInterface
{
    /**
     * Get all properties with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Find property by ID
     *
     * @param int $id
     * @return Property|null
     */
    public function findById(int $id): ?Property;
    
    /**
     * Create a new property
     *
     * @param array $attributes
     * @return Property
     */
    public function create(array $attributes): Property;
    
    /**
     * Update a property
     *
     * @param int $id
     * @param array $attributes
     * @return Property|null
     */
    public function update(int $id, array $attributes): ?Property;
    
    /**
     * Delete a property
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
    
    /**
     * Get properties by owner ID
     *
     * @param int $ownerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByOwnerId(int $ownerId, int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Get properties by type
     *
     * @param string $type
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByType(string $type, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get property statistics
     */
    public function getStatistics(): array;

    /**
     * Search properties with criteria
     */
    public function search(array $criteria, int $perPage = 15): LengthAwarePaginator;
} 