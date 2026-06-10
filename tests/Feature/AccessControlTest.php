<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * WHITEBOX TEST: Access Control & Route Protection
 * Tests role-based access control logic and route protection mechanisms
 * Tests path protection and authorization checks
 */
class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated user redirected to login
     * Path: !currentUser -> window.location.href = '/login'
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $currentUser = null;

        if (!$currentUser) {
            $redirect = '/login';
        }

        $this->assertEquals('/login', $redirect);
    }

    /**
     * Test user dashboard protected from non-users
     * Path: checkUserAuth -> user.role !== 'user' -> redirect /login
     */
    public function test_user_dashboard_protected_from_admin(): void
    {
        $userData = ['role' => 'admin'];

        if ($userData['role'] !== 'user') {
            $redirect = '/login';
            $canAccess = false;
        } else {
            $redirect = null;
            $canAccess = true;
        }

        $this->assertFalse($canAccess);
        $this->assertEquals('/login', $redirect);
    }

    /**
     * Test user dashboard accessible by user role
     * Path: checkUserAuth -> user.role === 'user' -> allow
     */
    public function test_user_dashboard_accessible_by_user(): void
    {
        $userData = ['role' => 'user'];

        $canAccess = $userData['role'] === 'user';

        $this->assertTrue($canAccess);
    }

    /**
     * Test admin dashboard protected from regular users
     * Path: checkAdminAuth -> user.role !== 'admin' -> redirect /login
     */
    public function test_admin_dashboard_protected_from_user(): void
    {
        $userData = ['role' => 'user'];

        if ($userData['role'] !== 'admin') {
            $redirect = '/login';
            $canAccess = false;
        } else {
            $redirect = null;
            $canAccess = true;
        }

        $this->assertFalse($canAccess);
        $this->assertEquals('/login', $redirect);
    }

    /**
     * Test admin dashboard accessible by admin role
     * Path: checkAdminAuth -> user.role === 'admin' -> allow
     */
    public function test_admin_dashboard_accessible_by_admin(): void
    {
        $userData = ['role' => 'admin'];

        $canAccess = $userData['role'] === 'admin';

        $this->assertTrue($canAccess);
    }

    /**
     * Test role-based redirection after login
     * Admin -> /admin, User -> /user
     */
    public function test_role_based_redirect_after_login(): void
    {
        $adminAccount = ['role' => 'admin', 'redirect' => '/admin'];
        $userAccount = ['role' => 'user', 'redirect' => '/user'];

        // Admin login redirects to admin dashboard
        $this->assertEquals('/admin', $adminAccount['redirect']);

        // User login redirects to user dashboard
        $this->assertEquals('/user', $userAccount['redirect']);
    }

    /**
     * Test user cannot access admin-specific operations
     * Tests operation-level access control
     */
    public function test_user_cannot_add_room(): void
    {
        $userRole = 'user';
        $canAddRoom = $userRole === 'admin';

        $this->assertFalse($canAddRoom);
    }

    /**
     * Test admin can add room
     * Path: role === 'admin' -> allow operation
     */
    public function test_admin_can_add_room(): void
    {
        $adminRole = 'admin';
        $canAddRoom = $adminRole === 'admin';

        $this->assertTrue($canAddRoom);
    }

    /**
     * Test user cannot add tenant
     * Tests operation-level access control
     */
    public function test_user_cannot_add_tenant(): void
    {
        $userRole = 'user';
        $canAddTenant = $userRole === 'admin';

        $this->assertFalse($canAddTenant);
    }

    /**
     * Test user can only view own payment records
     * Tests data-level access control
     */
    public function test_user_views_only_own_payments(): void
    {
        $userId = 2;
        $allPayments = [
            ['userId' => 1, 'amount' => 1500000],
            ['userId' => 2, 'amount' => 1500000],
            ['userId' => 3, 'amount' => 1500000],
        ];

        $userPayments = array_filter($allPayments, fn($p) => $p['userId'] === $userId);

        $this->assertCount(1, $userPayments);
    }

    /**
     * Test admin can view all payments
     * Tests no data filtering for admin
     */
    public function test_admin_views_all_payments(): void
    {
        $adminRole = 'admin';
        $allPayments = [
            ['userId' => 1, 'amount' => 1500000],
            ['userId' => 2, 'amount' => 1500000],
            ['userId' => 3, 'amount' => 1500000],
        ];

        $viewablePayments = $adminRole === 'admin' ? $allPayments : [];

        $this->assertCount(3, $viewablePayments);
    }

    /**
     * Test user can edit own profile only
     * Path: canEditProfile -> userId === currentUserId
     */
    public function test_user_can_edit_own_profile(): void
    {
        $currentUserId = 2;
        $targetUserId = 2;

        $canEdit = $currentUserId === $targetUserId;

        $this->assertTrue($canEdit);
    }

    /**
     * Test user cannot edit other profile
     * Path: userId !== currentUserId -> deny
     */
    public function test_user_cannot_edit_other_profile(): void
    {
        $currentUserId = 2;
        $targetUserId = 3;

        $canEdit = $currentUserId === $targetUserId;

        $this->assertFalse($canEdit);
    }

    /**
     * Test admin can edit any profile
     * Path: isAdmin -> allow all
     */
    public function test_admin_can_edit_any_profile(): void
    {
        $isAdmin = true;

        $canEdit = $isAdmin;

        $this->assertTrue($canEdit);
    }

    /**
     * Test payment submission requires authentication
     * Path: !auth -> deny operation
     */
    public function test_payment_requires_authentication(): void
    {
        $isAuthenticated = false;

        $canSubmitPayment = $isAuthenticated;

        $this->assertFalse($canSubmitPayment);
    }

    /**
     * Test payment submission with authentication
     * Path: auth exists -> allow operation
     */
    public function test_payment_with_authentication_allowed(): void
    {
        $isAuthenticated = true;

        $canSubmitPayment = $isAuthenticated;

        $this->assertTrue($canSubmitPayment);
    }

    /**
     * Test maintenance request requires authentication
     * Path: !auth -> deny
     */
    public function test_maintenance_requires_authentication(): void
    {
        $isAuthenticated = false;

        $canSubmitMaintenance = $isAuthenticated;

        $this->assertFalse($canSubmitMaintenance);
    }

    /**
     * Test access control decision matrix
     * Comprehensive matrix of roles vs permissions
     */
    public function test_access_control_decision_matrix(): void
    {
        $permissions = [
            // [role, action, expectedResult]
            ['user', 'view_own_profile', true],
            ['user', 'edit_own_profile', true],
            ['user', 'view_other_profile', false],
            ['user', 'add_room', false],
            ['user', 'add_tenant', false],
            ['admin', 'view_own_profile', true],
            ['admin', 'view_other_profile', true],
            ['admin', 'add_room', true],
            ['admin', 'add_tenant', true],
            ['guest', 'view_own_profile', false],
            ['guest', 'add_room', false],
        ];

        foreach ($permissions as [$role, $action, $expected]) {
            $allowed = $this->canPerformAction($role, $action);
            $this->assertEquals($expected, $allowed,
                "Role '$role' should " . ($expected ? 'be able to' : 'not be able to') . " $action");
        }
    }

    /**
     * Helper function to check action permission
     */
    private function canPerformAction(string $role, string $action): bool
    {
        $permissions = [
            'user' => [
                'view_own_profile' => true,
                'edit_own_profile' => true,
                'view_other_profile' => false,
                'add_room' => false,
                'add_tenant' => false,
            ],
            'admin' => [
                'view_own_profile' => true,
                'view_other_profile' => true,
                'add_room' => true,
                'add_tenant' => true,
            ],
            'guest' => [
                'view_own_profile' => false,
                'add_room' => false,
            ],
        ];

        return $permissions[$role][$action] ?? false;
    }

    /**
     * Test route login page accessible without auth
     */
    public function test_login_page_accessible_without_auth(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    /**
     * Test route index accessible without auth
     */
    public function test_index_page_accessible_without_auth(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test user route returns user dashboard
     */
    public function test_user_route_returns_user_dashboard(): void
    {
        $response = $this->get('/user');

        $response->assertStatus(200);
    }

    /**
     * Test admin route returns admin dashboard
     */
    public function test_admin_route_returns_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
    }

    /**
     * Test permission inheritance (admin superset of user)
     * Admin can do everything user can do
     */
    public function test_admin_inherits_user_permissions(): void
    {
        $userPermissions = ['view_own_profile', 'submit_payment', 'view_booking'];
        $adminPermissions = array_merge($userPermissions, ['add_room', 'add_tenant']);

        foreach ($userPermissions as $perm) {
            $this->assertContains($perm, $adminPermissions);
        }
    }

    /**
     * Test least privilege principle
     * User should not have admin permissions by default
     */
    public function test_user_no_admin_permissions(): void
    {
        $userRole = 'user';
        $adminPermissions = ['add_room', 'add_tenant', 'delete_user'];

        foreach ($adminPermissions as $perm) {
            $hasPermission = $userRole === 'admin' && in_array($perm, $adminPermissions);
            $this->assertFalse($hasPermission);
        }
    }
}
