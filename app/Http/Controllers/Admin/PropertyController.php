<?php

namespace App\Http\Controllers\Admin;

use App\Application\Services\PropertyService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyStoreRequest;
use App\Http\Requests\PropertyUpdateRequest;
use App\Domain\Property\Models\Property;
use App\Models\PropertyType;
use App\Models\PropertyStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    protected PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
        // Add middleware for admin authentication here if needed
        // $this->middleware('auth:admin'); // Example
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $filters = $this->buildFilters($request);
            $perPage = $request->get('per_page', 20);
            
            $properties = $this->propertyService->getAllProperties($filters, $perPage);
            $statistics = $this->propertyService->getStatistics();
            
            return view('admin.properties.index', compact('properties', 'statistics', 'filters'));
        } catch (\Exception $e) {
            Log::error('Error loading properties index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading properties. Please try again.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $propertyTypes = PropertyType::active()->ordered()->get();
        $propertyStatuses = PropertyStatus::active()->ordered()->get();
        
        return view('admin.properties.create', compact('propertyTypes', 'propertyStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PropertyStoreRequest $request)
    {
        try {
            $this->propertyService->createProperty($request->validated());
            return redirect()->route('admin.properties.index')->with('success', 'Property created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating property: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error creating property. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $property = $this->propertyService->getPropertyById($id);
        if (!$property) {
            return redirect()->route('admin.properties.index')->with('error', 'Property not found.');
        }
        
        return view('admin.properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $property = $this->propertyService->getPropertyById($id);
        if (!$property) {
            return redirect()->route('admin.properties.index')->with('error', 'Property not found.');
        }
        
        $propertyTypes = PropertyType::active()->ordered()->get();
        $propertyStatuses = PropertyStatus::active()->ordered()->get();
        
        return view('admin.properties.edit', compact('property', 'propertyTypes', 'propertyStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PropertyUpdateRequest $request, string $id)
    {
        try {
            $this->propertyService->updateProperty($id, $request->validated());
            return redirect()->route('admin.properties.index')->with('success', 'Property updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating property ' . $id . ': ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error updating property. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->propertyService->deleteProperty($id);
            return redirect()->route('admin.properties.index')->with('success', 'Property deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting property ' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Error deleting property. Please try again.');
        }
    }

    /**
     * Build filters from request
     */
    private function buildFilters(Request $request): array
    {
        return array_filter([
            'search' => $request->get('search'),
            'type' => $request->get('type'),
            'status' => $request->get('status'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'bedrooms' => $request->get('bedrooms'),
        ]);
    }
} 