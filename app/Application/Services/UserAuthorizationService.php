<?php

namespace App\Application\Services;

use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * User Authorization Service
 * 
 * Handles authorization logic for user operations
 */
class UserAuthorizationService
{
    /**
     * Check if current user can view users (customers)
     */
    public function canViewUsers(): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Only admin can view customer users
        return $this->isAdmin($user);
    }

    /**
     * Check if current user can view specific user
     */
    public function canViewUser(User $targetUser): bool
    {
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return false;
        }

        // Admin can view any user
        if ($this->isAdmin($currentUser)) {
            return true;
        }

        // Users can view their own profile
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        // Agents can view buyers/sellers they work with
        if ($this->isAgent($currentUser) && $this->canAgentViewUser($currentUser, $targetUser)) {
            return true;
        }

        return false;
    }

    /**
     * Check if current user can create customer users
     */
    public function canCreateUsers(): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Only admin can create customer users
        return $this->isAdmin($user);
    }

    /**
     * Check if current user can edit specific user
     */
    public function canEditUser(User $targetUser): bool
    {
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return false;
        }

        // Admin can edit any user
        if ($this->isAdmin($currentUser)) {
            return true;
        }

        // Users can edit their own profile (with restrictions)
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        return false;
    }

    /**
     * Check if current user can delete customer user
     */
    public function canDeleteUser(User $targetUser): bool
    {
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return false;
        }

        // Only admin can delete customer users
        if (!$this->isAdmin($currentUser)) {
            return false;
        }

        // Cannot delete another admin user from customer management
        if ($this->isAdmin($targetUser)) {
            return false;
        }

        return true;
    }

    /**
     * Check if current user can perform bulk actions
     */
    public function canPerformBulkActions(): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Only admins can perform bulk actions
        return $this->isAdmin($user);
    }

    /**
     * Check if current user can modify user status
     */
    public function canModifyUserStatus(User $targetUser): bool
    {
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return false;
        }

        // Only admin can modify user status
        if (!$this->isAdmin($currentUser)) {
            return false;
        }

        // Cannot modify your own status
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        return true;
    }

    /**
     * Check if current user can verify users
     */
    public function canVerifyUsers(): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Only admins can verify users
        return $this->isAdmin($user);
    }

    /**
     * Check if current user can export user data
     */
    public function canExportUsers(): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Admin and agents can export user data
        return $this->isAdmin($user) || $this->isAgent($user);
    }

    /**
     * Get allowed fields for editing based on user role
     */
    public function getAllowedEditFields(User $currentUser, User $targetUser): array
    {
        $baseFields = [
            'first_name', 'last_name', 'phone', 'date_of_birth', 'gender',
            'bio', 'occupation', 'company', 'address', 'city', 'state',
            'postal_code', 'country', 'receive_notifications', 'receive_marketing'
        ];

        // If editing own profile
        if ($currentUser->id === $targetUser->id) {
            return array_merge($baseFields, ['email', 'password']);
        }

        // If admin editing another user
        if ($this->isAdmin($currentUser)) {
            return array_merge($baseFields, [
                'email', 'password', 'user_type', 'status', 'is_verified',
                'license_number', 'agency_name', 'commission_rate'
            ]);
        }

        return [];
    }

    /**
     * Check if user is admin
     */
    private function isAdmin(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Check if user is agent
     */
    private function isAgent(User $user): bool
    {
        return $user->user_type === 'agent';
    }

    /**
     * Check if user is super admin (if implemented)
     */
    private function isSuperAdmin(User $user): bool
    {
        // This could be based on a specific field or role
        // For now, we'll use a simple check
        return $user->user_type === 'admin' && $user->email === 'super@admin.com';
    }

    /**
     * Check if agent can view specific user
     */
    private function canAgentViewUser(User $agent, User $targetUser): bool
    {
        // Agents can view buyers and sellers
        // This could be expanded to check for actual business relationships
        return in_array($targetUser->user_type, ['buyer', 'seller']);
    }

    /**
     * Check if current user can access admin panel
     */
    public function canAccessAdminPanel(): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Admin and agents can access admin panel
        return in_array($user->user_type, ['admin', 'agent']);
    }

    /**
     * Check if user can impersonate another user
     */
    public function canImpersonateUser(User $targetUser): bool
    {
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return false;
        }

        // Only super admin can impersonate
        if (!$this->isSuperAdmin($currentUser)) {
            return false;
        }

        // Cannot impersonate yourself
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        // Cannot impersonate other super admins
        if ($this->isSuperAdmin($targetUser)) {
            return false;
        }

        return true;
    }
}