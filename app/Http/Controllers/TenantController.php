<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Room;
use App\Models\TenantProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'max:30'],
            'room_id' => [
                'required',
                Rule::exists('rooms', 'id')->where(fn ($query) => $query->where('status', 'available')),
            ],
            'lease_start' => ['required', 'date'],
            'lease_end' => ['nullable', 'date', 'after_or_equal:lease_start'],
            'identity_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated) {
            $room = Room::whereKey($validated['room_id'])
                ->where('status', 'available')
                ->lockForUpdate()
                ->firstOrFail();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'user',
            ]);

            TenantProfile::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'phone' => $validated['phone'],
                'identity_number' => $validated['identity_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'lease_start' => $validated['lease_start'],
                'lease_end' => $validated['lease_end'] ?? null,
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
}
