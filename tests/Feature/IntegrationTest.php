<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\cr;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    /**
     * Test user can access login page
     */
    public function test_user_can_access_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    /**
     * Test user can access booking page
     */
    public function test_user_can_access_booking_page(): void
    {
        $response = $this->get('/booking');

        $response->assertStatus(200);
        $response->assertViewIs('booking');
    }

    /**
     * Test admin dashboard is accessible
     */
    public function test_admin_dashboard_is_accessible(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertViewIs('admin.admin-dashboard');
    }

    /**
     * Test user dashboard displays for authenticated user
     */
    public function test_user_dashboard_displays_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/user');

        $response->assertStatus(200);
        $response->assertViewIs('user.user-dashboard');
    }

    /**
     * Test index page displays user dashboard via controller
     */
    public function test_index_page_displays_user_dashboard_via_controller(): void
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
        $response->assertViewIs('user.user-dashboard');
    }

    /**
     * Test home page displays index view
     */
    public function test_home_page_displays_index_view(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('index');
    }

    /**
     * Test multiple users can be created for testing
     */
    public function test_multiple_test_users_can_be_created(): void
    {
        $users = User::factory()->count(3)->create();

        $this->assertCount(3, $users);

        foreach ($users as $user) {
            $this->assertNotNull($user->id);
            $this->assertNotNull($user->email);
            $this->assertNotNull($user->name);
        }
    }

    /**
     * Test user creation with specific data
     */
    public function test_user_creation_with_specific_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test cr model relationships (if any)
     */
    public function test_cr_model_can_be_instantiated(): void
    {
        $cr = new cr();

        $this->assertInstanceOf(cr::class, $cr);
    }
}
