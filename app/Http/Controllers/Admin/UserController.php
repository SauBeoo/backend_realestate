<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\User\Models\User;
use App\Application\Services\UserService;
use App\Application\Services\UserResponseService;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserBulkActionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Customer Management Controller
 *
 * Handles customer user management operations for the admin panel.
 * Manages buyer, seller, and agent accounts (excludes admin users).
 * Follows clean architecture principles with proper separation of concerns,
 * dependency injection, and SOLID principles.
 *
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * Uses dependency injection to inject required services
     */
    public function __construct(
        private UserService $userService,
        private UserResponseService $responseService
    ) {
        // Simple middleware for basic authentication
//        $this->middleware('auth');
    }

    /**
     * Display a listing of customer users with search and filtering capabilities.
     *
     * @param Request $request The HTTP request containing filters
     * @return View|JsonResponse The customer users index view or JSON response for API
     *
     * @throws \Exception If customer user listing fails
     */
    public function index(Request $request): View|JsonResponse|RedirectResponse
    {
        try {
            // Extract and validate filters
            $filters = $this->extractFilters($request);

            // Get paginated users
            $users = $this->userService->getPaginatedUsers($filters, $request->get('per_page', 20));

            // Get statistics
            $statistics = $this->userService->getUserStatistics();

            // Return appropriate response format
            if ($request->expectsJson()) {
                return $this->responseService->successResponse(
                    'Users retrieved successfully',
                    [
                        'users' => $this->responseService->formatPaginatedUsers($users, true),
                        'statistics' => $this->responseService->formatStatistics($statistics)
                    ]
                );
            }

            return $this->responseService->renderIndexView($users, $statistics);

        } catch (Throwable $e) {
            Log::error('Error retrieving users list', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->exceptionResponse($e, 'Failed to retrieve users');
            }

            return $this->responseService->redirectWithError('admin.dashboard', 'Failed to load users list.');
        }
    }

    /**
     * Show the form for creating a new user.
     *
     * @return View|JsonResponse
     */
    public function create(): View|JsonResponse|RedirectResponse
    {
        try {
            return $this->responseService->renderCreateView();

        } catch (Throwable $e) {
            Log::error('Error loading user creation form', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->responseService->redirectWithError('admin.users.index', 'Failed to load user creation form.');
        }
    }

    /**
     * Store a newly created user in storage.
     *
     * @param UserStoreRequest $request Validated request with user data
     * @return RedirectResponse|JsonResponse
     */
    public function store(UserStoreRequest $request): RedirectResponse|JsonResponse
    {
        try {
            // Create user through service
            $user = $this->userService->createUser($request);

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'created_by' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->createdResponse(
                    'User created successfully',
                    $this->responseService->formatUserData($user, true)
                );
            }

            return $this->responseService->redirectWithSuccess(
                'admin.users.index',
                'User created successfully.'
            );

        } catch (Throwable $e) {
            Log::error('Error creating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->safe()->toArray()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->exceptionResponse($e, 'Failed to create user');
            }

            return $this->responseService->backWithError('Error creating user. Please try again.', true);
        }
    }

    /**
     * Display the specified user.
     *
     * @param User $user The user model instance
     * @param Request $request
     * @return View|JsonResponse
     */
    public function show(User $user, Request $request): View|JsonResponse|RedirectResponse
    {
        try {
            // Get user with relationships and statistics
            $userWithRelations = $this->userService->getUserWithRelations($user->id, ['properties']);
            $userStats = $this->userService->getUserStats($userWithRelations);

            if ($request->expectsJson()) {
                return $this->responseService->successResponse(
                    'User details retrieved successfully',
                    [
                        'user' => $this->responseService->formatUserData($userWithRelations, true),
                        'statistics' => $userStats
                    ]
                );
            }

            return $this->responseService->renderShowView($userWithRelations, $userStats);

        } catch (Throwable $e) {
            Log::error('Error retrieving user details', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->exceptionResponse($e, 'Failed to retrieve user details');
            }

            return $this->responseService->redirectWithError('admin.users.index', 'Failed to load user details.');
        }
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param User $user The user model instance
     * @return View|JsonResponse
     */
    public function edit(User $user): View|JsonResponse|RedirectResponse
    {
        try {
            return $this->responseService->renderEditView($user);

        } catch (Throwable $e) {
            Log::error('Error loading user edit form', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->responseService->redirectWithError('admin.users.index', 'Failed to load user edit form.');
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param UserUpdateRequest $request Validated request with updated user data
     * @param User $user The user model instance
     * @return RedirectResponse|JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse|JsonResponse
    {
        try {
            // Update user through service
            $updatedUser = $this->userService->updateUser($user, $request);

            Log::info('User updated successfully', [
                'user_id' => $user->id,
                'updated_by' => auth()->id(),
                'changes' => $request->safe()->toArray()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->successResponse(
                    'User updated successfully',
                    $this->responseService->formatUserData($updatedUser, true)
                );
            }

            return $this->responseService->redirectWithSuccess(
                'admin.users.index',
                'User updated successfully.'
            );

        } catch (Throwable $e) {
            Log::error('Error updating user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->safe()->toArray()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->exceptionResponse($e, 'Failed to update user');
            }

            return $this->responseService->backWithError('Error updating user. Please try again.', true);
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user The user model instance
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(User $user, Request $request): RedirectResponse|JsonResponse
    {
        try {
            // Delete user through service
            $this->userService->deleteUser($user);

            Log::info('User deleted successfully', [
                'user_id' => $user->id,
                'deleted_by' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->successResponse('User deleted successfully');
            }

            return $this->responseService->redirectWithSuccess(
                'admin.users.index',
                'User deleted successfully.'
            );

        } catch (\InvalidArgumentException $e) {
            Log::warning('User deletion blocked due to business rules', [
                'user_id' => $user->id,
                'reason' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->errorResponse($e->getMessage(), null, 422);
            }

            return $this->responseService->backWithError($e->getMessage());

        } catch (Throwable $e) {
            Log::error('Error deleting user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->exceptionResponse($e, 'Failed to delete user');
            }

            return $this->responseService->backWithError('Error deleting user. Please try again.');
        }
    }

    /**
     * Get user data for AJAX requests with search functionality.
     *
     * @param Request $request The HTTP request with search parameters
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->get('search', '');
            $limit = min($request->get('limit', 10), 50); // Cap at 50 results

            if (empty($searchTerm)) {
                return $this->responseService->successResponse('Search term required', []);
            }

            $users = $this->userService->searchUsers($searchTerm, $limit);

            return $this->responseService->successResponse(
                'User data retrieved successfully',
                $this->responseService->formatUsersCollection($users)
            );

        } catch (Throwable $e) {
            Log::error('Error retrieving user data for AJAX', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all()
            ]);

            return $this->responseService->exceptionResponse($e, 'Failed to retrieve user data');
        }
    }

    /**
     * Perform bulk operations on multiple users.
     *
     * @param UserBulkActionRequest $request Validated request with bulk action data
     * @return RedirectResponse|JsonResponse
     */
    public function bulkAction(UserBulkActionRequest $request): RedirectResponse|JsonResponse
    {
        try {
            // Perform bulk action through service
            $result = $this->userService->performBulkAction($request);

            Log::info('Bulk action performed successfully', [
                'action' => $request->validated()['action'],
                'user_ids' => $request->validated()['user_ids'],
                'affected_count' => $result['affected_count'],
                'performed_by' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->successResponse(
                    $result['message'],
                    $this->responseService->formatBulkActionResult($result)
                );
            }

            return $this->responseService->backWithSuccess($result['message']);

        } catch (\InvalidArgumentException $e) {
            Log::warning('Bulk action blocked due to business rules', [
                'action' => $request->validated()['action'] ?? 'unknown',
                'reason' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->errorResponse($e->getMessage(), null, 422);
            }

            return $this->responseService->backWithError($e->getMessage());

        } catch (Throwable $e) {
            Log::error('Error performing bulk action', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->safe()->toArray()
            ]);

            if ($request->expectsJson()) {
                return $this->responseService->exceptionResponse($e, 'Failed to perform bulk action');
            }

            return $this->responseService->backWithError('Error performing bulk action. Please try again.');
        }
    }

    /**
     * Extract and validate filters from the request.
     *
     * @param Request $request The HTTP request
     * @return array The validated filters
     */
    private function extractFilters(Request $request): array
    {
        return [
            'search' => $request->get('search'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'status' => $request->get('status'),
            'user_type' => $request->get('user_type'),
            'is_verified' => $request->boolean('is_verified'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];
    }
}
