<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\PropertySearchApplicationService;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyCollection;
use App\Http\Resources\PropertyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertySearchController extends Controller
{
    /**
     * @var PropertySearchApplicationService
     */
    protected $propertySearchService;

    /**
     * PropertySearchController constructor.
     *
     * @param PropertySearchApplicationService $propertySearchService
     */
    public function __construct(PropertySearchApplicationService $propertySearchService)
    {
        $this->propertySearchService = $propertySearchService;
    }

    /**
     * Search properties with filters
     *
     * @param Request $request
     * @return PropertyCollection
     */
    public function search(Request $request): PropertyCollection
    {
        $filters = $request->only([
            'type',
            'min_price',
            'max_price',
            'bedrooms',
            'bathrooms',
            'min_area',
            'max_area',
            'status',
        ]);
        
        $perPage = $request->get('per_page', 15);
        
        $properties = $this->propertySearchService->searchProperties($filters, $perPage);
        
        return new PropertyCollection($properties);
    }

    /**
     * Find properties near a location
     *
     * @param Request $request
     * @return PropertyCollection|JsonResponse
     */
    public function nearby(Request $request): PropertyCollection|JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_km' => 'nullable|numeric|min:0.1|max:50',
        ]);
        
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radiusKm = $request->input('radius_km', 5.0);
        
        $additionalFilters = $request->only([
            'type',
            'min_price',
            'max_price',
            'status',
        ]);
        
        $perPage = $request->get('per_page', 15);
        
        $properties = $this->propertySearchService->findPropertiesNearby(
            $latitude,
            $longitude,
            $radiusKm,
            $additionalFilters,
            $perPage
        );
        
        return new PropertyCollection($properties);
    }

    /**
     * Get similar properties to a given property
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function similar(int $id, Request $request): JsonResponse
    {
        $limit = $request->get('limit', 3);
        
        $similarProperties = $this->propertySearchService->getSimilarProperties($id, $limit);
        
        return response()->json([
            'data' => PropertyResource::collection($similarProperties),
        ]);
    }
} 