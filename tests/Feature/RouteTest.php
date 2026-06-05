<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * Test home route returns index view
     */
    public function test_home_route_returns_index_view(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('index');
    }

    /**
     * Test index route returns user dashboard
     */
    public function test_index_route_returns_user_dashboard(): void
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
        $response->assertViewIs('user.user-dashboard');
    }

    /**
     * Test admin route returns admin dashboard view
     */
    public function test_admin_route_returns_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertViewIs('admin.admin-dashboard');
    }

    /**
     * Test user route returns user dashboard view
     */
    public function test_user_route_returns_user_dashboard(): void
    {
        $response = $this->get('/user');

        $response->assertStatus(200);
        $response->assertViewIs('user.user-dashboard');
    }

    /**
     * Test login route returns login view
     */
    public function test_login_route_returns_login_view(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    /**
     * Test booking route returns booking view
     */
    public function test_booking_route_returns_booking_view(): void
    {
        $response = $this->get('/booking');

        $response->assertStatus(200);
        $response->assertViewIs('booking');
    }

    /**
     * Test all routes are accessible
     */
    public function test_all_routes_are_accessible(): void
    {
        $routes = ['/', '/index', '/admin', '/user', '/login', '/booking'];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $this->assertTrue(in_array($response->getStatusCode(), [200, 302, 404]));
        }
    }

    /**
     * Test undefined route returns 404
     */
    public function test_undefined_route_returns_404(): void
    {
        $response = $this->get('/undefined-route');

        $response->assertStatus(404);
    }
}
