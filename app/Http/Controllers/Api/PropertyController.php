<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\PropertyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\PropertyCollection;
use App\Http\Requests\PropertyStoreRequest;
use App\Http\Requests\PropertyUpdateRequest;

class PropertyController extends Controller
{
    /**
     * @var PropertyService
     */
    protected $propertyService;

    /**
     * PropertyController constructor.
     *
     * @param PropertyService $propertyService
     */
    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * Display a listing of properties.
     *
     * @param Request $request
     * @return PropertyCollection
     */
    public function index(Request $request): PropertyCollection
    {
        $filters = $request->only(['type', 'min_price', 'max_price', 'bedrooms', 'status']);
        $perPage = $request->get('per_page', 15);
        
        $properties = $this->propertyService->getAllProperties($filters, $perPage);
        
        return new PropertyCollection($properties);
    }

    /**
     * Display the specified property.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $property = $this->propertyService->getPropertyById($id);
        
        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }
        
        return response()->json([
            'data' => new PropertyResource($property)
        ]);
    }

    /**
     * Store a newly created property.
     *
     * @param PropertyStoreRequest $request
     * @return JsonResponse
     */
    public function store(PropertyStoreRequest $request): JsonResponse
    {
        $property = $this->propertyService->createProperty($request->validated());
        
        return response()->json([
            'message' => 'Property created successfully',
            'data' => new PropertyResource($property)
        ], 201);
    }

    /**
     * Update the specified property.
     *
     * @param PropertyUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PropertyUpdateRequest $request, int $id): JsonResponse
    {
        $property = $this->propertyService->updateProperty($id, $request->validated());
        
        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }
        
        return response()->json([
            'message' => 'Property updated successfully',
            'data' => new PropertyResource($property)
        ]);
    }

    /**
     * Remove the specified property.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->propertyService->deleteProperty($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Property not found'], 404);
        }
        
        return response()->json(['message' => 'Property deleted successfully']);
    }

    /**
     * Get properties by type.
     *
     * @param string $type
     * @param Request $request
     * @return PropertyCollection
     */
    public function getByType(string $type, Request $request): PropertyCollection
    {
        $perPage = $request->get('per_page', 15);
        $properties = $this->propertyService->getPropertiesByType($type, $perPage);
        
        return new PropertyCollection($properties);
    }

    /**
     * Get properties by owner.
     *
     * @param int $ownerId
     * @param Request $request
     * @return PropertyCollection
     */
    public function getByOwner(int $ownerId, Request $request): PropertyCollection
    {
        $perPage = $request->get('per_page', 15);
        $properties = $this->propertyService->getPropertiesByOwner($ownerId, $perPage);
        
        return new PropertyCollection($properties);
    }
} 