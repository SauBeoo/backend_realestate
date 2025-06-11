<?php

namespace App\Application\Services;

use App\Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

/**
 * User Response Service
 * 
 * Handles response formatting and view rendering for user operations
 */
class UserResponseService
{
    /**
     * Format success response for API
     */
    public function successResponse(string $message, mixed $data = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Format error response for API
     */
    public function errorResponse(string $message, mixed $errors = null, int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Format exception response
     */
    public function exceptionResponse(Throwable $exception, string $fallbackMessage = 'An error occurred'): JsonResponse
    {
        $message = config('app.debug') ? $exception->getMessage() : $fallbackMessage;
        
        return $this->errorResponse($message, null, 500);
    }

    /**
     * Render users index view
     */
    public function renderIndexView(LengthAwarePaginator $users, array $statistics): View
    {
        return view('admin.users.index', compact('users', 'statistics'));
    }

    /**
     * Render user create view
     */
    public function renderCreateView(): View
    {
        return view('admin.users.create');
    }

    /**
     * Render user show view
     */
    public function renderShowView(User $user, array $userStats): View
    {
        return view('admin.users.show', compact('user', 'userStats'));
    }

    /**
     * Render user edit view
     */
    public function renderEditView(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Redirect with success message
     */
    public function redirectWithSuccess(string $route, string $message, array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with('success', $message);
    }

    /**
     * Redirect with error message
     */
    public function redirectWithError(string $route, string $message, array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with('error', $message);
    }

    /**
     * Redirect back with error message
     */
    public function backWithError(string $message, bool $withInput = true): RedirectResponse
    {
        $redirect = back()->with('error', $message);
        
        if ($withInput) {
            $redirect = $redirect->withInput();
        }

        return $redirect;
    }

    /**
     * Redirect back with success message
     */
    public function backWithSuccess(string $message): RedirectResponse
    {
        return back()->with('success', $message);
    }

    /**
     * Format user data for API response
     */
    public function formatUserData(User $user, bool $includeRelations = false): array
    {
        $data = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'initials' => $user->initials,
            'email' => $user->email,
            'phone' => $user->phone,
            'date_of_birth' => $user->date_of_birth?->format('Y-m-d'),
            'gender' => $user->gender,
            'avatar' => $user->avatar,
            'bio' => $user->bio,
            'occupation' => $user->occupation,
            'company' => $user->company,
            'address' => $user->address,
            'city' => $user->city,
            'state' => $user->state,
            'postal_code' => $user->postal_code,
            'country' => $user->country,
            'user_type' => $user->user_type,
            'status' => $user->status,
            'is_verified' => $user->is_verified,
            'license_number' => $user->license_number,
            'agency_name' => $user->agency_name,
            'commission_rate' => $user->commission_rate,
            'receive_notifications' => $user->receive_notifications,
            'receive_marketing' => $user->receive_marketing,
            'last_login_at' => $user->last_login_at?->toISOString(),
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString(),
        ];

        if ($includeRelations) {
            $data['properties_count'] = $user->properties_count ?? $user->properties()->count();
            
            if ($user->relationLoaded('properties')) {
                $data['properties'] = $user->properties->map(function ($property) {
                    return [
                        'id' => $property->id,
                        'title' => $property->title,
                        'status' => $property->status,
                        'price' => $property->price,
                        'created_at' => $property->created_at->toISOString(),
                    ];
                });
            }
        }

        return $data;
    }

    /**
     * Format users collection for API response
     */
    public function formatUsersCollection(Collection $users, bool $includeRelations = false): array
    {
        return $users->map(function (User $user) use ($includeRelations) {
            return $this->formatUserData($user, $includeRelations);
        })->toArray();
    }

    /**
     * Format paginated users for API response
     */
    public function formatPaginatedUsers(LengthAwarePaginator $users, bool $includeRelations = false): array
    {
        return [
            'data' => collect($users->items())->map(function (User $user) use ($includeRelations) {
                return $this->formatUserData($user, $includeRelations);
            })->toArray(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
                'has_more_pages' => $users->hasMorePages(),
            ],
            'links' => [
                'first' => $users->url(1),
                'last' => $users->url($users->lastPage()),
                'prev' => $users->previousPageUrl(),
                'next' => $users->nextPageUrl(),
            ],
        ];
    }

    /**
     * Format user statistics for response
     */
    public function formatStatistics(array $statistics): array
    {
        return [
            'total_users' => $statistics['total_users'] ?? 0,
            'new_this_month' => $statistics['new_this_month'] ?? 0,
            'active_users' => $statistics['active_users'] ?? 0,
            'verified_users' => $statistics['verified_users'] ?? 0,
            'users_with_properties' => $statistics['users_with_properties'] ?? 0,
            'users_by_type' => $statistics['users_by_type'] ?? [],
            'users_by_status' => $statistics['users_by_status'] ?? [],
        ];
    }

    /**
     * Format bulk action result
     */
    public function formatBulkActionResult(array $result): array
    {
        return [
            'affected_count' => $result['affected_count'],
            'message' => $result['message'],
            'success' => true,
        ];
    }

    /**
     * Handle validation errors for API
     */
    public function validationErrorResponse(array $errors): JsonResponse
    {
        return $this->errorResponse(
            'Validation failed',
            $errors,
            422
        );
    }

    /**
     * Handle not found response
     */
    public function notFoundResponse(string $resource = 'User'): JsonResponse
    {
        return $this->errorResponse(
            "{$resource} not found",
            null,
            404
        );
    }

    /**
     * Handle unauthorized response
     */
    public function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, null, 403);
    }

    /**
     * Handle created response
     */
    public function createdResponse(string $message, mixed $data = null): JsonResponse
    {
        return $this->successResponse($message, $data, 201);
    }
}