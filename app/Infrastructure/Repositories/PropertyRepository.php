<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Property\Models\Property;
use App\Domain\Property\Repositories\PropertyRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

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
        $query = Property::with(['owner', 'propertyType', 'propertyStatus']);
        
        $this->applyFilters($query, $filters);
        
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
        return Property::with(['owner', 'propertyType', 'propertyStatus'])->find($id);
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
        $property = Property::find($id);
        
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
        $property = Property::find($id);
        
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
        return Property::whereHas('propertyType', function ($query) use ($type) {
            $query->where('key', $type);
        })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get property statistics
     */
    public function getStatistics(): array
    {
        $total = Property::count();
        
        // Get statistics by status using relationships
        $byStatus = Property::with('propertyStatus')
            ->get()
            ->groupBy(function($property) {
                return $property->propertyStatus?->key ?? 'unknown';
            })
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();
        
        // Get statistics by type using relationships
        $byType = Property::with('propertyType')
            ->get()
            ->groupBy(function($property) {
                return $property->propertyType?->key ?? 'unknown';
            })
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        return [
            'total' => $total,
            'available' => ($byStatus['for_sale'] ?? 0) + ($byStatus['for_rent'] ?? 0),
            'pending' => $byStatus['pending'] ?? 0,
            'sold' => $byStatus['sold'] ?? 0,
            'rented' => $byStatus['rented'] ?? 0,
            'for_sale' => $byStatus['for_sale'] ?? 0,
            'for_rent' => $byStatus['for_rent'] ?? 0,
            'by_type' => $byType,
            'by_status' => $byStatus,
            'average_price' => Property::avg('price') ?? 0,
        ];
    }

    /**
     * Search properties with criteria
     */
    public function search(array $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = Property::with(['owner']);
        
        $this->applyFilters($query, $criteria);
        
        return $query->latest()->paginate($perPage);
    }

    /**
     * Apply filters to query
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['type'])) {
            $query->whereHas('propertyType', function ($q) use ($filters) {
                $q->where('key', $filters['type']);
            });
        }
        
        if (!empty($filters['status'])) {
            $query->whereHas('propertyStatus', function ($q) use ($filters) {
                $q->where('key', $filters['status']);
            });
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
    }
} 