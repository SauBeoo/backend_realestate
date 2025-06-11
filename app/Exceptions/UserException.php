<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * User-specific Exception Handler
 * 
 * Handles user-related exceptions with proper error codes and messages
 */
class UserException extends Exception
{
    protected string $userMessage;
    protected array $context;

    public function __construct(
        string $message = 'User operation failed',
        string $userMessage = null,
        int $code = 0,
        array $context = [],
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        
        $this->userMessage = $userMessage ?? $message;
        $this->context = $context;
    }

    /**
     * Create a user not found exception
     */
    public static function notFound(int $userId = null): self
    {
        return new self(
            'User not found',
            'The requested user could not be found.',
            404,
            ['user_id' => $userId]
        );
    }

    /**
     * Create an unauthorized access exception
     */
    public static function unauthorized(string $action = 'perform this action'): self
    {
        return new self(
            'Unauthorized user access',
            "You are not authorized to {$action}.",
            403
        );
    }

    /**
     * Create a validation failed exception
     */
    public static function validationFailed(array $errors = []): self
    {
        return new self(
            'User validation failed',
            'The provided data is invalid.',
            422,
            ['validation_errors' => $errors]
        );
    }

    /**
     * Create a user creation failed exception
     */
    public static function creationFailed(string $reason = 'Unknown error'): self
    {
        return new self(
            'User creation failed',
            'Failed to create the user account.',
            500,
            ['reason' => $reason]
        );
    }

    /**
     * Create a user update failed exception
     */
    public static function updateFailed(int $userId, string $reason = 'Unknown error'): self
    {
        return new self(
            'User update failed',
            'Failed to update the user account.',
            500,
            ['user_id' => $userId, 'reason' => $reason]
        );
    }

    /**
     * Create a user deletion failed exception
     */
    public static function deletionFailed(int $userId, string $reason = 'Unknown error'): self
    {
        return new self(
            'User deletion failed',
            'Failed to delete the user account.',
            500,
            ['user_id' => $userId, 'reason' => $reason]
        );
    }

    /**
     * Create a business rule violation exception
     */
    public static function businessRuleViolation(string $rule, string $userMessage = null): self
    {
        return new self(
            "Business rule violation: {$rule}",
            $userMessage ?? 'This operation violates business rules.',
            422,
            ['rule' => $rule]
        );
    }

    /**
     * Create a user has dependencies exception
     */
    public static function hasDependencies(int $userId, string $dependency = 'properties'): self
    {
        return new self(
            'User has dependencies',
            "Cannot delete user because they have associated {$dependency}.",
            422,
            ['user_id' => $userId, 'dependency' => $dependency]
        );
    }

    /**
     * Get the user-friendly message
     */
    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    /**
     * Get the exception context
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Render the exception for HTTP response
     */
    public function render($request): JsonResponse|RedirectResponse|Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getUserMessage(),
                'error_code' => $this->getCode(),
                'context' => config('app.debug') ? $this->getContext() : null,
            ], $this->getCode() ?: 500);
        }

        // For web requests, redirect back with error
        return back()
            ->withInput()
            ->with('error', $this->getUserMessage());
    }

    /**
     * Report the exception for logging
     */
    public function report(): void
    {
        Log::error($this->getMessage(), [
            'user_message' => $this->getUserMessage(),
            'context' => $this->getContext(),
            'trace' => $this->getTraceAsString(),
        ]);
    }
}