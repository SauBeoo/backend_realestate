<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

/**
 * User Management Controller
 * 
 * Handles comprehensive user management functionality for admin panel
 * Implements CRUD operations with proper validation and error handling
 */
class UserController extends Controller
{
    public function __construct()
    {
        // Add admin authentication middleware
        // $this->middleware('auth:admin');
    }

    /**
     * Display a listing of users with search and filtering
     */
    public function index(Request $request)
    {
        $query = User::with(['properties']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by registration date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(20)->appends($request->query());

        $statistics = [
            'total_users' => User::count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)
                                   ->whereYear('created_at', now()->year)
                                   ->count(),
            'active_users' => User::where('updated_at', '>=', now()->subDays(30))->count(),
            'users_with_properties' => User::has('properties')->count(),
        ];

        return view('admin.users.index', compact('users', 'statistics'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            $validated['password'] = Hash::make($validated['password']);
            
            $user = User::create($validated);

            return redirect()->route('admin.users.index')
                           ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return back()->withInput()
                        ->with('error', 'Error creating user. Please try again.');
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['properties' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $userStats = [
            'total_properties' => $user->properties->count(),
            'properties_for_sale' => $user->properties->where('status', 'for_sale')->count(),
            'properties_for_rent' => $user->properties->where('status', 'for_rent')->count(),
            'sold_properties' => $user->properties->where('status', 'sold')->count(),
            'rented_properties' => $user->properties->where('status', 'rented')->count(),
            'total_property_value' => $user->properties->sum('price'),
        ];

        return view('admin.users.show', compact('user', 'userStats'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return redirect()->route('admin.users.index')
                           ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return back()->withInput()
                        ->with('error', 'Error updating user. Please try again.');
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        try {
            // Check if user has properties
            if ($user->properties()->count() > 0) {
                return back()->with('error', 'Cannot delete user with associated properties. Please reassign or delete properties first.');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                           ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Error deleting user. Please try again.');
        }
    }

    /**
     * Get user data for AJAX requests
     */
    public function getData(Request $request)
    {
        try {
            $users = User::select(['id', 'name', 'email', 'created_at'])
                        ->when($request->filled('search'), function ($query) use ($request) {
                            $search = $request->get('search');
                            $query->where('name', 'LIKE', "%{$search}%")
                                  ->orWhere('email', 'LIKE', "%{$search}%");
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch users'], 500);
        }
    }

    /**
     * Bulk operations on users
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            $userIds = $validated['user_ids'];
            $action = $validated['action'];

            switch ($action) {
                case 'delete':
                    // Check for users with properties
                    $usersWithProperties = User::whereIn('id', $userIds)
                                              ->has('properties')
                                              ->count();
                    
                    if ($usersWithProperties > 0) {
                        return back()->with('error', 'Cannot delete users with associated properties.');
                    }
                    
                    User::whereIn('id', $userIds)->delete();
                    $message = 'Selected users deleted successfully.';
                    break;

                case 'activate':
                    User::whereIn('id', $userIds)->update(['email_verified_at' => now()]);
                    $message = 'Selected users activated successfully.';
                    break;

                case 'deactivate':
                    User::whereIn('id', $userIds)->update(['email_verified_at' => null]);
                    $message = 'Selected users deactivated successfully.';
                    break;
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Bulk action error: ' . $e->getMessage());
            return back()->with('error', 'Error performing bulk action.');
        }
    }
}