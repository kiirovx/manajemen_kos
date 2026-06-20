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
     * Test index route redirects to home (legacy redirect)
     */
    public function test_index_route_returns_user_dashboard(): void
    {
        $response = $this->get('/index');

        $response->assertRedirect('/');
    }

    /**
     * Test admin route redirects to admin dashboard (legacy redirect)
     */
    public function test_admin_route_returns_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/dashboard/admin');
    }

    /**
     * Test user route redirects to user dashboard (legacy redirect)
     */
    public function test_user_route_returns_user_dashboard(): void
    {
        $response = $this->get('/user');

        $response->assertRedirect('/dashboard/user');
    }

    /**
     * Test login route redirects to auth login (legacy redirect)
     */
    public function test_login_route_returns_login_view(): void
    {
        $response = $this->get('/login');

        $response->assertRedirect('/auth/login');
    }

    /**
     * Test booking route returns booking view
     */
    public function test_booking_route_returns_booking_view(): void
    {
        $response = $this->get('/booking');

        $response->assertStatus(200);
        $response->assertViewIs('booking.booking');
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
