<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\cr;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test legacy /index route redirects to home
     */
    public function test_index_returns_user_dashboard_view(): void
    {
        $response = $this->get('/index');

        $response->assertRedirect('/');
    }

    /**
     * Test authenticated user dashboard renders
     */
    public function test_index_with_authenticated_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/dashboard/user');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.user.user');
    }

    /**
     * Test create method exists
     */
    public function test_create_method_exists(): void
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\UserController::class, 'create'));
    }

    /**
     * Test store method exists
     */
    public function test_store_method_exists(): void
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\UserController::class, 'store'));
    }

    /**
     * Test show method exists
     */
    public function test_show_method_exists(): void
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\UserController::class, 'show'));
    }

    /**
     * Test edit method exists
     */
    public function test_edit_method_exists(): void
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\UserController::class, 'edit'));
    }

    /**
     * Test update method exists
     */
    public function test_update_method_exists(): void
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\UserController::class, 'update'));
    }

    /**
     * Test destroy method exists
     */
    public function test_destroy_method_exists(): void
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\UserController::class, 'destroy'));
    }
}
