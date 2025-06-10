<?php

namespace App\Http\Controllers\Admin;

use App\Application\Services\PropertyService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyStoreRequest;
use App\Http\Requests\PropertyUpdateRequest;
use App\Domain\Property\Models\Property; // Assuming direct model usage for simplicity in admin, or use service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // For logging errors

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
        // You might want to use the service if complex filtering/pagination is needed
        // For simplicity, direct model usage is shown here, adjust as per your DDD layers.
        $properties = Property::latest()->paginate(20); // Or $this->propertyService->getAllProperties(...);
        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch any necessary data for the form, e.g., owners (users), property types, statuses
        // $users = User::all(); // Example
        // For simplicity, we'll handle enums/options directly in the view or with constants
        return view('admin.properties.create');
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $property = $this->propertyService->getPropertyById($id);
        if (!$property) {
            return redirect()->route('admin.properties.index')->with('error', 'Property not found.');
        }
        // $users = User::all(); // Example if you need to select owner
        return view('admin.properties.edit', compact('property'));
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
} 