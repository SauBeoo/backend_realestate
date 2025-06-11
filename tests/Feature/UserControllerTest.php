<?php

namespace Tests\Feature;

use App\Domain\User\Models\User;
use App\Application\Services\UserService;
use App\Application\Services\UserAuthorizationService;
use App\Application\Services\UserResponseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * User Controller Feature Tests
 * 
 * Tests the refactored UserController with clean architecture
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;
    protected User $testUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test admin user
        $this->adminUser = User::factory()->create([
            'user_type' => 'admin',
            'status' => 'active',
            'is_verified' => true,
        ]);

        // Create test regular user
        $this->testUser = User::factory()->create([
            'user_type' => 'buyer',
            'status' => 'active',
            'is_verified' => false,
        ]);
    }

    /** @test */
    public function admin_can_view_users_index()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.index'));

        $response->assertStatus(200)
            ->assertViewIs('admin.users.index')
            ->assertViewHas(['users', 'statistics']);
    }

    /** @test */
    public function non_admin_cannot_access_users_index()
    {
        $response = $this->actingAs($this->testUser)
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_user()
    {
        $userData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'buyer',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.users.store'), $userData);

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
        ]);
    }

    /** @test */
    public function user_creation_validates_required_fields()
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.users.store'), []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'password']);
    }

    /** @test */
    public function admin_can_update_user()
    {
        $updateData = [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => $this->testUser->email,
            'user_type' => $this->testUser->user_type,
            'status' => $this->testUser->status,
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.users.update', $this->testUser), $updateData);

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->testUser->refresh();
        $this->assertEquals('Updated', $this->testUser->first_name);
        $this->assertEquals('Name', $this->testUser->last_name);
    }

    /** @test */
    public function admin_can_delete_user_without_properties()
    {
        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.users.destroy', $this->testUser));

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('users', ['id' => $this->testUser->id]);
    }

    /** @test */
    public function search_functionality_works()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.index', ['search' => $this->testUser->first_name]));

        $response->assertStatus(200)
            ->assertSee($this->testUser->full_name);
    }

    /** @test */
    public function admin_can_perform_bulk_actions()
    {
        $users = User::factory()->count(3)->create(['user_type' => 'buyer']);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.users.bulk-action'), [
                'action' => 'activate',
                'user_ids' => $users->pluck('id')->toArray(),
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        foreach ($users as $user) {
            $user->refresh();
            $this->assertEquals('active', $user->status);
            $this->assertNotNull($user->email_verified_at);
        }
    }

    /** @test */
    public function api_endpoints_return_json()
    {
        $response = $this->actingAs($this->adminUser)
            ->getJson(route('admin.users.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'users' => [
                        'data',
                        'meta',
                        'links'
                    ],
                    'statistics'
                ]
            ]);
    }

    /** @test */
    public function unauthorized_api_access_returns_403()
    {
        $response = $this->actingAs($this->testUser)
            ->getJson(route('admin.users.index'));

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Insufficient permissions to view users.'
            ]);
    }

    /** @test */
    public function user_service_is_properly_injected()
    {
        $userService = app(UserService::class);
        $this->assertInstanceOf(UserService::class, $userService);
    }

    /** @test */
    public function authorization_service_is_properly_injected()
    {
        $authService = app(UserAuthorizationService::class);
        $this->assertInstanceOf(UserAuthorizationService::class, $authService);
    }

    /** @test */
    public function response_service_is_properly_injected()
    {
        $responseService = app(UserResponseService::class);
        $this->assertInstanceOf(UserResponseService::class, $responseService);
    }
}