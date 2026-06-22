<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Room;
use App\Models\TenantProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'user')),
                Rule::unique('tenant_profiles', 'user_id'),
            ],
            'phone' => ['required', 'string', 'max:30'],
            'room_id' => [
                'required',
                Rule::exists('rooms', 'id')->where(fn ($query) => $query->where('status', 'available')),
            ],
            'lease_start' => ['required', 'date'],
            'lease_end' => ['nullable', 'date', 'after_or_equal:lease_start'],
            'identity_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
        ], [
            'user_id.unique' => 'Pengguna ini sudah terdaftar sebagai penyewa.',
        ]);

        DB::transaction(function () use ($validated) {
            $room = Room::whereKey($validated['room_id'])
                ->where('status', 'available')
                ->lockForUpdate()
                ->firstOrFail();

            $user = User::whereKey($validated['user_id'])
                ->where('role', 'user')
                ->firstOrFail();

            TenantProfile::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'phone' => $validated['phone'],
                'identity_number' => $validated['identity_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'lease_start' => $validated['lease_start'],
                'lease_end' => $validated['lease_end'] ?? null,
                'status' => 'Aktif',
            ]);

            $room->update(['status' => 'occupied']);

            Payment::create([
                'user_id' => $user->id,
                'period_label' => now()->format('F Y'),
                'amount' => $room->price,
                'due_date' => $validated['lease_start'],
                'status' => 'pending',
            ]);
        });

        return redirect(route('dashboard.admin') . '#manajemen-penyewa')
            ->with('success', 'Penyewa berhasil ditambahkan.');
    }

    public function update(Request $request, TenantProfile $tenant)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'room_id' => [
                'required',
                Rule::exists('rooms', 'id')->where(function ($query) use ($tenant) {
                    $query->where('status', 'available')->orWhere('id', $tenant->room_id);
                }),
            ],
            'lease_start' => ['required', 'date'],
            'lease_end' => ['nullable', 'date', 'after_or_equal:lease_start'],
            'identity_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $tenant) {
            $oldRoomId = $tenant->room_id;
            $newRoomId = (int) $validated['room_id'];

            $tenant->update([
                'phone' => $validated['phone'],
                'room_id' => $newRoomId,
                'identity_number' => $validated['identity_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'lease_start' => $validated['lease_start'],
                'lease_end' => $validated['lease_end'] ?? null,
            ]);

            if ($newRoomId !== (int) $oldRoomId) {
                Room::whereKey($oldRoomId)->update(['status' => 'available']);
                Room::whereKey($newRoomId)->update(['status' => 'occupied']);
            }
        });

        return redirect(route('dashboard.admin') . '#manajemen-penyewa')
            ->with('success', 'Data penyewa berhasil diperbarui.');
    }

    /**
     * Admin: Terima penyewa dari review booking.
     */
    public function approve(TenantProfile $tenant)
    {
        if ($tenant->status !== 'Menunggu Persetujuan') {
            return redirect(route('dashboard.admin') . '#booking-review')
                ->with('error', 'Penyewa sudah tidak dalam status Menunggu Persetujuan.');
        }

        DB::transaction(function () use ($tenant) {
            $tenant->update([
                'status' => 'Aktif',
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            // Update status kamar
            $room = $tenant->room;
            if ($room && $room->status !== 'occupied') {
                $room->update(['status' => 'occupied']);
            }

            Log::info('Tenant approved by admin', [
                'tenant_id' => $tenant->id,
                'user_id' => $tenant->user_id,
                'room_id' => $tenant->room_id,
            ]);
        });

        return redirect(route('dashboard.admin') . '#booking-review')
            ->with('success', 'Penyewa berhasil diterima. Sekarang berstatus Aktif.');
    }

    /**
     * Admin: Tolak penyewa dari review booking.
     */
    public function reject(Request $request, TenantProfile $tenant)
    {
        if ($tenant->status !== 'Menunggu Persetujuan') {
            return redirect(route('dashboard.admin') . '#booking-review')
                ->with('error', 'Penyewa sudah tidak dalam status Menunggu Persetujuan.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($tenant, $validated) {
            $tenant->update([
                'status' => 'Ditolak',
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            Log::info('Tenant rejected by admin', [
                'tenant_id' => $tenant->id,
                'user_id' => $tenant->user_id,
                'reason' => $validated['rejection_reason'],
            ]);
        });

        return redirect(route('dashboard.admin') . '#booking-review')
            ->with('success', 'Penyewa telah ditolak.');
    }

    /**
     * Admin: Tandai penyewa sudah keluar (check-out).
     */
    public function checkOut(TenantProfile $tenant)
    {
        if (!in_array($tenant->status, ['Aktif', 'Nonaktif'])) {
            return redirect(route('dashboard.admin') . '#manajemen-penyewa')
                ->with('error', 'Hanya penyewa Aktif yang dapat di-check-out.');
        }

        DB::transaction(function () use ($tenant) {
            $tenant->update([
                'status' => 'Keluar',
                'checked_out_at' => now(),
            ]);

            // Kembalikan kamar ke status available
            $room = $tenant->room;
            if ($room && $room->status === 'occupied') {
                $room->update(['status' => 'available']);
                $room->increaseSlot();
            }

            Log::info('Tenant checked out', [
                'tenant_id' => $tenant->id,
                'user_id' => $tenant->user_id,
                'room_id' => $tenant->room_id,
            ]);
        });

        return redirect(route('dashboard.admin') . '#manajemen-penyewa')
            ->with('success', 'Penyewa berhasil di-check-out. Kamar dikembalikan ke status tersedia.');
    }
}