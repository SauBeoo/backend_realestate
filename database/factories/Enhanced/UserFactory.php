<?php

namespace Database\Factories\Enhanced;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Enhanced User Factory for the new user structure
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => $this->faker->optional()->phoneNumber(),
            'date_of_birth' => $this->faker->optional()->date('Y-m-d', '-18 years'),
            'gender' => $this->faker->optional()->randomElement(['male', 'female', 'other']),
            'avatar' => null,
            'bio' => $this->faker->optional()->paragraph(),
            'occupation' => $this->faker->optional()->jobTitle(),
            'company' => $this->faker->optional()->company(),
            'address' => $this->faker->optional()->address(),
            'city' => $this->faker->optional()->city(),
            'state' => $this->faker->optional()->state(),
            'postal_code' => $this->faker->optional()->postcode(),
            'country' => $this->faker->country(),
            'user_type' => $this->faker->randomElement(['buyer', 'seller', 'agent', 'admin']),
            'status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'is_verified' => $this->faker->boolean(70), // 70% chance of being verified
            'license_number' => null,
            'agency_name' => null,
            'commission_rate' => null,
            'preferences' => null,
            'receive_notifications' => $this->faker->boolean(80), // 80% chance
            'receive_marketing' => $this->faker->boolean(30), // 30% chance
            'last_login_at' => $this->faker->optional()->dateTimeBetween('-1 month'),
            'last_login_ip' => $this->faker->optional()->ipv4(),
            'password_changed_at' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'admin',
            'status' => 'active',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the user is an agent.
     */
    public function agent(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'agent',
            'status' => 'active',
            'is_verified' => true,
            'license_number' => $this->faker->bothify('LIC###???'),
            'agency_name' => $this->faker->company() . ' Realty',
            'commission_rate' => $this->faker->randomFloat(2, 1.0, 10.0),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the user is a seller.
     */
    public function seller(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'seller',
            'status' => 'active',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the user is a buyer.
     */
    public function buyer(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'buyer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the user is unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'is_verified' => false,
        ]);
    }

    /**
     * Indicate that the user is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user has complete profile information.
     */
    public function withCompleteProfile(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->date('Y-m-d', '-25 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'bio' => $this->faker->paragraph(3),
            'occupation' => $this->faker->jobTitle(),
            'company' => $this->faker->company(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => 'Vietnam',
        ]);
    }

    /**
     * Indicate that the user prefers marketing communications.
     */
    /**
     * Indicate that the user prefers marketing communications.
     */
    public function likesMarketing(): static
    {
        return $this->state(fn (array $attributes) => [
            'receive_marketing' => true,
            'receive_notifications' => true,
        ]);
    }

    /**
     * Indicate that the user has preferences set.
     */
    public function withPreferences(): static
    {
        return $this->state(fn (array $attributes) => [
            'preferences' => json_encode([
                'language' => $this->faker->randomElement(['en', 'vi']),
                'currency' => $this->faker->randomElement(['USD', 'VND']),
                'theme' => $this->faker->randomElement(['light', 'dark']),
                'notifications' => [
                    'email' => $this->faker->boolean(),
                    'sms' => $this->faker->boolean(),
                    'push' => $this->faker->boolean(),
                ],
                'property_alerts' => [
                    'price_range' => [
                        'min' => $this->faker->numberBetween(100000, 500000),
                        'max' => $this->faker->numberBetween(500000, 2000000),
                    ],
                    'location' => $this->faker->city(),
                    'property_type' => $this->faker->randomElement(['house', 'apartment', 'condo', 'land']),
                ],
            ]),
        ]);
    }
}