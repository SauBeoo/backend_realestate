<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * User Repository Implementation
 *
 * Handles database operations for customer users (buyer, seller, agent)
 * Excludes admin users from customer management operations
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users with optional filtering and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = User::with(['properties'])
            ->where('user_type', '!=', 'admin'); // Exclude admin users

        // Apply search filter
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Apply date filters
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->withStatus($filters['status']);
        }

        // Apply user type filter (only customer types)
        if (!empty($filters['user_type']) && $filters['user_type'] !== 'admin') {
            $query->ofType($filters['user_type']);
        }

        // Apply verified filter
        if (isset($filters['is_verified'])) {
            if ($filters['is_verified']) {
                $query->verified();
            } else {
                $query->where('is_verified', false);
            }
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Create a new user
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update an existing user
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Delete a user
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Get users by IDs
     */
    public function findByIds(array $ids): Collection
    {
        return User::whereIn('id', $ids)->get();
    }

    /**
     * Bulk update users
     */
    public function bulkUpdate(array $ids, array $data): int
    {
        return User::whereIn('id', $ids)->update($data);
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(array $ids): int
    {
        return User::whereIn('id', $ids)->delete();
    }

    /**
     * Get users with properties count
     */
    public function getUsersWithPropertiesCount(array $userIds): Collection
    {
        return User::whereIn('id', $userIds)
            ->withCount('properties')
            ->get();
    }

    /**
     * Search customer users by term
     */
    public function search(string $term, int $limit = 10): Collection
    {
        return User::search($term)
            ->where('user_type', '!=', 'admin') // Exclude admin users
            ->limit($limit)
            ->get(['id', 'first_name', 'last_name', 'email', 'created_at']);
    }

    /**
     * Get user statistics
     */
    public function getStatistics(): array
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Only count customer users (exclude admin)
        $customerUsersQuery = User::where('user_type', '!=', 'admin');

        return [
            'total_users' => $customerUsersQuery->count(),
            'new_this_month' => $customerUsersQuery->whereMonth('created_at', $currentMonth)
                                   ->whereYear('created_at', $currentYear)
                                   ->count(),
            'active_users' => $customerUsersQuery->where('updated_at', '>=', now()->subDays(30))->count(),
            'verified_users' => $customerUsersQuery->where('is_verified', true)->count(),
            'users_with_properties' => $customerUsersQuery->has('properties')->count(),
            'users_by_type' => $customerUsersQuery->select('user_type', DB::raw('count(*) as count'))
                                  ->groupBy('user_type')
                                  ->pluck('count', 'user_type')
                                  ->toArray(),
            'users_by_status' => $customerUsersQuery->select('status', DB::raw('count(*) as count'))
                                    ->groupBy('status')
                                    ->pluck('count', 'status')
                                    ->toArray(),
        ];
    }

    /**
     * Get users by status
     */
    public function findByStatus(string $status): Collection
    {
        return User::withStatus($status)->get();
    }

    /**
     * Get users by type
     */
    public function findByType(string $type): Collection
    {
        return User::ofType($type)->get();
    }

    /**
     * Check if user has properties
     */
    public function hasProperties(User $user): bool
    {
        return $user->properties()->exists();
    }
}