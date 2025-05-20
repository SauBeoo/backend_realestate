<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Property\Models\Property;
use App\Domain\Property\Repositories\PropertyRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertyRepository implements PropertyRepositoryInterface
{
    /**
     * Get all properties with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Property::query();
        
        // Apply filters
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        
        if (!empty($filters['bedrooms'])) {
            $query->where('bedrooms', $filters['bedrooms']);
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        return $query->latest()->paginate($perPage);
    }
    
    /**
     * Find property by ID
     *
     * @param int $id
     * @return Property|null
     */
    public function findById(int $id): ?Property
    {
        return Property::find($id);
    }
    
    /**
     * Create a new property
     *
     * @param array $attributes
     * @return Property
     */
    public function create(array $attributes): Property
    {
        return Property::create($attributes);
    }
    
    /**
     * Update a property
     *
     * @param int $id
     * @param array $attributes
     * @return Property|null
     */
    public function update(int $id, array $attributes): ?Property
    {
        $property = $this->findById($id);
        
        if (!$property) {
            return null;
        }
        
        $property->update($attributes);
        
        return $property->fresh();
    }
    
    /**
     * Delete a property
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $property = $this->findById($id);
        
        if (!$property) {
            return false;
        }
        
        return $property->delete();
    }
    
    /**
     * Get properties by owner ID
     *
     * @param int $ownerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByOwnerId(int $ownerId, int $perPage = 15): LengthAwarePaginator
    {
        return Property::where('owner_id', $ownerId)
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get properties by type
     *
     * @param string $type
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByType(string $type, int $perPage = 15): LengthAwarePaginator
    {
        return Property::where('type', $type)
            ->latest()
            ->paginate($perPage);
    }
} 