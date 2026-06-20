<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\TenantProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_assign_existing_user_as_tenant(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create(['role' => 'user']);
        $room = Room::create([
            'number' => '101',
            'type' => 'standard',
            'price' => 1500000,
            'floor' => 1,
            'status' => 'available',
        ]);

        $response = $this->actingAs($admin)->post('/dashboard/admin/tenants', [
            'user_id' => $user->id,
            'phone' => '0812-3456-7890',
            'room_id' => $room->id,
            'lease_start' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('dashboard.admin') . '#manajemen-penyewa');

        $this->assertDatabaseHas('tenant_profiles', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'phone' => '0812-3456-7890',
        ]);
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'status' => 'occupied',
        ]);
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    public function test_cannot_assign_user_that_is_already_a_tenant(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create(['role' => 'user']);
        $roomA = Room::create([
            'number' => '101', 'type' => 'standard', 'price' => 1500000, 'floor' => 1, 'status' => 'available',
        ]);
        $roomB = Room::create([
            'number' => '102', 'type' => 'standard', 'price' => 1500000, 'floor' => 1, 'status' => 'available',
        ]);

        TenantProfile::create([
            'user_id' => $user->id,
            'room_id' => $roomA->id,
            'phone' => '0800',
            'lease_start' => now()->toDateString(),
        ]);

        $response = $this->actingAs($admin)->post('/dashboard/admin/tenants', [
            'user_id' => $user->id,
            'phone' => '0812',
            'room_id' => $roomB->id,
            'lease_start' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('user_id');
        $this->assertDatabaseMissing('tenant_profiles', [
            'user_id' => $user->id,
            'room_id' => $roomB->id,
        ]);
    }

    public function test_cannot_assign_admin_account_as_tenant(): void
    {
        $admin = $this->admin();
        $otherAdmin = User::factory()->create(['role' => 'admin']);
        $room = Room::create([
            'number' => '101', 'type' => 'standard', 'price' => 1500000, 'floor' => 1, 'status' => 'available',
        ]);

        $response = $this->actingAs($admin)->post('/dashboard/admin/tenants', [
            'user_id' => $otherAdmin->id,
            'phone' => '0812',
            'room_id' => $room->id,
            'lease_start' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('user_id');
    }

    public function test_dashboard_excludes_existing_tenants_from_available_users(): void
    {
        $admin = $this->admin();
        $freeUser = User::factory()->create(['role' => 'user', 'name' => 'Free User']);
        $tenantUser = User::factory()->create(['role' => 'user', 'name' => 'Tenant User']);
        $room = Room::create([
            'number' => '101', 'type' => 'standard', 'price' => 1500000, 'floor' => 1, 'status' => 'occupied',
        ]);

        TenantProfile::create([
            'user_id' => $tenantUser->id,
            'room_id' => $room->id,
            'phone' => '0800',
            'lease_start' => now()->toDateString(),
        ]);

        $response = $this->actingAs($admin)->get('/dashboard/admin');

        $response->assertStatus(200);
        $response->assertViewHas('availableUsers', function ($users) use ($freeUser, $tenantUser) {
            return $users->contains('id', $freeUser->id)
                && ! $users->contains('id', $tenantUser->id);
        });
    }
}
