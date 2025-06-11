<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * User Repository Interface
 * 
 * Defines the contract for user data access operations
 */
interface UserRepositoryInterface
{
    /**
     * Get all users with optional filtering and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User;

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create a new user
     */
    public function create(array $data): User;

    /**
     * Update an existing user
     */
    public function update(User $user, array $data): bool;

    /**
     * Delete a user
     */
    public function delete(User $user): bool;

    /**
     * Get users by IDs
     */
    public function findByIds(array $ids): Collection;

    /**
     * Bulk update users
     */
    public function bulkUpdate(array $ids, array $data): int;

    /**
     * Bulk delete users
     */
    public function bulkDelete(array $ids): int;

    /**
     * Get users with properties count
     */
    public function getUsersWithPropertiesCount(array $userIds): Collection;

    /**
     * Search users by term
     */
    public function search(string $term, int $limit = 10): Collection;

    /**
     * Get user statistics
     */
    public function getStatistics(): array;

    /**
     * Get users by status
     */
    public function findByStatus(string $status): Collection;

    /**
     * Get users by type
     */
    public function findByType(string $type): Collection;

    /**
     * Check if user has properties
     */
    public function hasProperties(User $user): bool;
}