<?php

namespace App\Application\Services;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserBulkActionRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * User Service
 *
 * Handles business logic for user operations (buyer, seller, agent)
 * Simple CRUD operations for customer users
 */
class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get paginated users with filters
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->userRepository->getAllWithFilters($filters, $perPage);
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Get user by ID with relationships
     */
    public function getUserWithRelations(int $id, array $relations = ['properties']): ?User
    {
        $user = $this->userRepository->findById($id);
        
        if ($user && !empty($relations)) {
            $user->load($relations);
        }

        return $user;
    }

    /**
     * Create a new customer user
     */
    public function createUser(UserStoreRequest $request): User
    {
        $data = $request->validated();
        
        // Hash password
        $data['password'] = Hash::make($data['password']);
        
        // Set default values for customer users
        $data['user_type'] = $data['user_type'] ?? 'buyer';
        $data['status'] = $data['status'] ?? 'active';
        $data['receive_notifications'] = $data['receive_notifications'] ?? true;
        $data['receive_marketing'] = $data['receive_marketing'] ?? false;
        $data['country'] = $data['country'] ?? 'Vietnam';
        

        return DB::transaction(function () use ($data) {
            return $this->userRepository->create($data);
        });
    }

    /**
     * Update an existing customer user
     */
    public function updateUser(User $user, UserUpdateRequest $request): User
    {
        $data = $request->validated();
        
        // Handle password update
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            $data['password_changed_at'] = now();
        } else {
            unset($data['password']);
        }

        DB::transaction(function () use ($user, $data) {
            $this->userRepository->update($user, $data);
        });

        return $user->fresh();
    }

    /**
     * Delete a customer user
     */
    public function deleteUser(User $user): bool
    {
        // Check if user has properties
        if ($this->userRepository->hasProperties($user)) {
            throw new \InvalidArgumentException('Cannot delete user with associated properties. Please transfer or delete properties first.');
        }

        return DB::transaction(function () use ($user) {
            return $this->userRepository->delete($user);
        });
    }

    /**
     * Perform bulk actions on users
     */
    public function performBulkAction(UserBulkActionRequest $request): array
    {
        $data = $request->validated();
        $userIds = $data['user_ids'];
        $action = $data['action'];

        return DB::transaction(function () use ($userIds, $action) {
            $affectedCount = 0;
            $message = '';

            switch ($action) {
                case 'delete':
                    // Check for users with properties
                    $usersWithProperties = $this->userRepository->getUsersWithPropertiesCount($userIds);
                    $usersWithPropertiesCount = $usersWithProperties->where('properties_count', '>', 0)->count();
                    
                    if ($usersWithPropertiesCount > 0) {
                        throw new \InvalidArgumentException('Cannot delete users with associated properties.');
                    }
                    
                    $affectedCount = $this->userRepository->bulkDelete($userIds);
                    $message = "Successfully deleted {$affectedCount} users.";
                    break;

                case 'activate':
                    $updateData = [
                        'email_verified_at' => now(),
                        'status' => 'active'
                    ];
                    $affectedCount = $this->userRepository->bulkUpdate($userIds, $updateData);
                    $message = "Successfully activated {$affectedCount} users.";
                    break;

                case 'deactivate':
                    $updateData = [
                        'email_verified_at' => null,
                        'status' => 'inactive'
                    ];
                    $affectedCount = $this->userRepository->bulkUpdate($userIds, $updateData);
                    $message = "Successfully deactivated {$affectedCount} users.";
                    break;

                case 'suspend':
                    $updateData = ['status' => 'suspended'];
                    $affectedCount = $this->userRepository->bulkUpdate($userIds, $updateData);
                    $message = "Successfully suspended {$affectedCount} users.";
                    break;

                case 'unsuspend':
                    $updateData = ['status' => 'active'];
                    $affectedCount = $this->userRepository->bulkUpdate($userIds, $updateData);
                    $message = "Successfully unsuspended {$affectedCount} users.";
                    break;

                default:
                    throw new \InvalidArgumentException("Unsupported bulk action: {$action}");
            }

            return [
                'affected_count' => $affectedCount,
                'message' => $message
            ];
        });
    }

    /**
     * Search users
     */
    public function searchUsers(string $term, int $limit = 10): Collection
    {
        return $this->userRepository->search($term, $limit);
    }

    /**
     * Get user statistics
     */
    public function getUserStatistics(): array
    {
        return $this->userRepository->getStatistics();
    }

    /**
     * Get user statistics for a specific user
     */
    public function getUserStats(User $user): array
    {
        $user->load(['properties']);

        // Load properties with status relationships for accurate counting
        $user->load(['properties.propertyStatus']);
        
        return [
            'total_properties' => $user->properties->count(),
            'properties_for_sale' => $user->properties->filter(function($property) {
                return $property->propertyStatus?->key === 'for_sale';
            })->count(),
            'properties_for_rent' => $user->properties->filter(function($property) {
                return $property->propertyStatus?->key === 'for_rent';
            })->count(),
            'sold_properties' => $user->properties->filter(function($property) {
                return $property->propertyStatus?->key === 'sold';
            })->count(),
            'rented_properties' => $user->properties->filter(function($property) {
                return $property->propertyStatus?->key === 'rented';
            })->count(),
            'total_property_value' => $user->properties->sum('price'),
            'average_property_value' => $user->properties->count() > 0 
                ? $user->properties->avg('price') 
                : 0,
            'latest_property_date' => $user->properties->max('created_at'),
        ];
    }

    /**
     * Verify a user
     */
    public function verifyUser(User $user): bool
    {
        return $this->userRepository->update($user, [
            'is_verified' => true,
            'email_verified_at' => now()
        ]);
    }

    /**
     * Unverify a user
     */
    public function unverifyUser(User $user): bool
    {
        return $this->userRepository->update($user, [
            'is_verified' => false,
            'email_verified_at' => null
        ]);
    }

    /**
     * Update user last login
     */
    public function updateLastLogin(User $user, string $ipAddress): bool
    {
        return $this->userRepository->update($user, [
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress
        ]);
    }

    /**
     * Get users by type
     */
    public function getUsersByType(string $type): Collection
    {
        return $this->userRepository->findByType($type);
    }

    /**
     * Get users by status
     */
    public function getUsersByStatus(string $status): Collection
    {
        return $this->userRepository->findByStatus($status);
    }
}