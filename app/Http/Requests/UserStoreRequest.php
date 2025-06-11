<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'occupation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'user_type' => 'nullable|in:buyer,seller,agent',
            'status' => 'nullable|in:active,inactive,suspended',
            'is_verified' => 'nullable|boolean',
            'license_number' => 'nullable|string|max:255',
            'agency_name' => 'nullable|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'preferences' => 'nullable|json',
            'receive_notifications' => 'nullable|boolean',
            'receive_marketing' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name cannot exceed 255 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.max' => 'Last name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'date_of_birth.date' => 'Please provide a valid date of birth.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'gender.in' => 'Gender must be one of: male, female, other.',
            'user_type.in' => 'User type must be one of: buyer, seller, agent.',
            'status.in' => 'Status must be one of: active, inactive, suspended.',
            'commission_rate.numeric' => 'Commission rate must be a number.',
            'commission_rate.min' => 'Commission rate cannot be negative.',
            'commission_rate.max' => 'Commission rate cannot exceed 100%.',
            'preferences.json' => 'Preferences must be in valid JSON format.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 'email address',
            'phone' => 'phone number',
            'date_of_birth' => 'date of birth',
            'user_type' => 'user type',
            'is_verified' => 'verification status',
            'license_number' => 'license number',
            'agency_name' => 'agency name',
            'commission_rate' => 'commission rate',
            'receive_notifications' => 'notification preference',
            'receive_marketing' => 'marketing preference',
        ];
    }
}