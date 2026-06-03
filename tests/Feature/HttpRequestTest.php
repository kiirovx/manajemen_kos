<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class HttpRequestTest extends TestCase
{
    /**
     * Test GET request to home route
     */
    public function test_get_home_route(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test GET request to index route
     */
    public function test_get_index_route(): void
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
    }

    /**
     * Test GET request to admin route
     */
    public function test_get_admin_route(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
    }

    /**
     * Test GET request to user route
     */
    public function test_get_user_route(): void
    {
        $response = $this->get('/user');

        $response->assertStatus(200);
    }

    /**
     * Test GET request to login route
     */
    public function test_get_login_route(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test GET request to booking route
     */
    public function test_get_booking_route(): void
    {
        $response = $this->get('/booking');

        $response->assertStatus(200);
    }

    /**
     * Test HTTP response headers are set correctly
     */
    public function test_http_response_has_correct_headers(): void
    {
        $response = $this->get('/');

        $this->assertTrue($response->headers->has('Content-Type'));
    }

    /**
     * Test GET request returns content
     */
    public function test_get_request_returns_content(): void
    {
        $response = $this->get('/');

        $this->assertNotEmpty($response->getContent());
    }

    /**
     * Test index route through UserController
     */
    public function test_user_controller_index_through_route(): void
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
        $response->assertViewIs('user.user-dashboard');
    }

    /**
     * Test route with authenticated user
     */
    public function test_route_with_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/user');

        $response->assertStatus(200);
    }

    /**
     * Test route without authentication
     */
    public function test_route_without_authentication(): void
    {
        $response = $this->get('/user');

        $response->assertStatus(200);
    }

    /**
     * Test HTTP method constraints
     */
    public function test_post_to_get_only_route(): void
    {
        $response = $this->post('/');

        // Should return 405 Method Not Allowed or similar
        $this->assertTrue(in_array($response->getStatusCode(), [404, 405]));
    }

    /**
     * Test route response view is properly set
     */
    public function test_route_response_view_is_properly_set(): void
    {
        $response = $this->get('/login');

        $response->assertViewIs('login');
    }
}
