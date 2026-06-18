<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\MaintenanceRequest;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Room;
use App\Models\TenantProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders_with_real_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $tenantUser = User::factory()->create(['role' => 'user', 'name' => 'Penyewa Satu']);
        $room = Room::create([
            'number' => '101', 'type' => 'standard', 'price' => 1500000, 'floor' => 1, 'status' => 'occupied',
        ]);
        Room::create([
            'number' => '102', 'type' => 'deluxe', 'price' => 2000000, 'floor' => 1, 'status' => 'available',
        ]);

        TenantProfile::create([
            'user_id' => $tenantUser->id,
            'room_id' => $room->id,
            'phone' => '0812',
            'lease_start' => now()->subMonth()->toDateString(),
            'lease_end' => now()->addYear()->toDateString(),
        ]);

        Payment::create([
            'user_id' => $tenantUser->id,
            'period_label' => now()->format('F Y'),
            'amount' => 1500000,
            'due_date' => now()->addDays(5),
            'status' => 'pending',
        ]);
        Payment::create([
            'user_id' => $tenantUser->id,
            'period_label' => now()->subMonth()->format('F Y'),
            'amount' => 1500000,
            'due_date' => now()->subMonth(),
            'paid_date' => now()->subMonth(),
            'status' => 'paid',
            'payment_method' => 'Transfer Bank',
        ]);

        ActivityLog::create([
            'user_id' => $tenantUser->id,
            'activity_type' => 'Pembayaran',
            'description' => 'Pembayaran bulan ini',
            'status' => 'Berhasil',
            'activity_date' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/dashboard/admin');

        $response->assertStatus(200);
        $response->assertViewHas('recentActivities');
        $response->assertViewHas('upcomingPayments');
        $response->assertSee('Penyewa Satu');
        $response->assertSee('Okupansi');
    }

    public function test_user_dashboard_renders_with_real_data(): void
    {
        $user = User::factory()->create(['role' => 'user', 'name' => 'User Render']);
        $room = Room::create([
            'number' => '201', 'type' => 'standard', 'price' => 1500000, 'floor' => 2, 'status' => 'occupied',
        ]);
        TenantProfile::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'phone' => '0812',
            'lease_start' => now()->subMonth()->toDateString(),
            'lease_end' => now()->addMonths(6)->toDateString(),
        ]);

        Payment::create([
            'user_id' => $user->id,
            'period_label' => now()->format('F Y'),
            'amount' => 1500000,
            'due_date' => now()->addDays(5),
            'status' => 'pending',
        ]);

        MaintenanceRequest::create([
            'user_id' => $user->id,
            'title' => 'Keran bocor',
            'category' => 'Plumbing (Air)',
            'description' => 'Air menetes terus',
            'priority' => 'normal',
            'status' => 'resolved',
            'submitted_at' => now()->subDays(3),
            'completed_at' => now()->subDay(),
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Selamat datang',
            'message' => 'Akun anda aktif',
            'type' => 'info',
            'is_read' => false,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'activity_type' => 'Maintenance',
            'description' => 'Ajukan perbaikan: Keran bocor',
            'status' => 'Selesai',
            'activity_date' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard/user');

        $response->assertStatus(200);
        $response->assertSee('User Render');
        $response->assertSee('Keran bocor');
        // Resolved maintenance should display as "Selesai"
        $response->assertSee('Selesai');
    }
}
