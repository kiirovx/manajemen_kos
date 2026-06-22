<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function store(Request $request)
    {
        $request->merge([
            'type' => strtolower((string) $request->input('type')),
        ]);

        $validated = $request->validate([
            'number' => ['required', 'string', 'max:255', 'unique:rooms,number'],
            'type' => ['required', 'string', Rule::in(['standard', 'deluxe', 'premium'])],
            'price' => ['required', 'numeric', 'min:0'],
            'floor' => ['required', 'integer', 'min:1'],
            'capacity' => ['required', 'integer', 'min:1'],
            'slots' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
            'photos' => ['nullable', 'string'],
            'photo_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        // Handle file upload — simpan ke public storage
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $filename = 'rooms/' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->storeAs(dirname($filename), basename($filename), 'public');
            $validated['photos'] = asset('storage/' . $filename);
        }

        // Jika tidak upload file tapi isi URL, gunakan URL
        if (empty($validated['photos']) && !empty($validated['photos_url'])) {
            $validated['photos'] = $validated['photos_url'];
        }

        // Pastikan field photos ada
        if (!isset($validated['photos'])) {
            $validated['photos'] = null;
        }

        Room::create($validated + ['status' => 'available']);

        return redirect(route('dashboard.admin') . '#manajemen-kamar')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function update(Request $request, Room $room)
    {
        $request->merge([
            'type' => strtolower((string) $request->input('type')),
        ]);

        $validated = $request->validate([
            'number' => ['required', 'string', 'max:255', Rule::unique('rooms', 'number')->ignore($room->id)],
            'type' => ['required', 'string', Rule::in(['standard', 'deluxe', 'premium'])],
            'price' => ['required', 'numeric', 'min:0'],
            'floor' => ['required', 'integer', 'min:1'],
            'capacity' => ['required', 'integer', 'min:1'],
            'slots' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
            'photos' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['available', 'occupied', 'maintenance'])],
        ]);

        // Handle file upload
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $filename = 'rooms/' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs($filename, '', 'public');
            $validated['photos'] = asset('storage/' . $filename);
        }

        $room->update($validated);

        return redirect(route('dashboard.admin') . '#manajemen-kamar')
            ->with('success', 'Kamar berhasil diperbarui.');
    }

    /**
     * Cek status booking untuk keperluan hapus kamar.
     * Hanya booking dengan status "Dibayar" yang dianggap aktif.
     * Booking pending/cancel/expired/deny/refund TIDAK memblokir.
     */
    public function bookingsStatus(Room $room)
    {
        // Status yang dianggap AKTIF (menghalangi penghapusan)
        $activeStatuses = ['Dibayar'];

        $activeBookings = $room->bookings()
            ->whereIn('status', $activeStatuses)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $activeCount = $activeBookings->count();

        // Semua booking (termasuk non-aktif) — untuk ditampilkan di modal
        $allBookings = $room->bookings()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $bookingsData = $allBookings->map(function (Booking $booking) {
            return [
                'id' => $booking->id,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'booking_id' => $booking->id,
                'booking_number' => 'BK-' . $booking->created_at->format('Ymd') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT),
                'room_name' => $booking->room_name,
                'room_price' => (float) $booking->room_price,
                'gross_amount' => (float) ($booking->gross_amount ?? $booking->room_price),
                'status' => $booking->status,
                'midtrans_transaction_status' => $booking->midtrans_transaction_status,
                'payment_method' => $booking->payment_method,
                'paid_at' => $booking->paid_at ? $booking->paid_at->format('d M Y H:i') : null,
                'created_at' => $booking->created_at->format('d M Y H:i'),
                'is_active' => in_array($booking->status, ['Dibayar']),
            ];
        });

        return response()->json([
            'active_count' => $activeCount,
            'total_bookings' => $allBookings->count(),
            'can_delete' => $activeCount === 0,
            'bookings' => $bookingsData,
        ]);
    }

    /**
     * Hapus kamar.
     */
    public function destroy(Room $room)
    {
        // Hanya booking dengan status "Dibayar" yang menghalangi
        $activeBookings = $room->bookings()
            ->whereIn('status', ['Dibayar'])
            ->count();

        if ($activeBookings > 0) {
            return redirect(route('dashboard.admin') . '#manajemen-kamar')
                ->with('error', 'Kamar tidak dapat dihapus karena masih memiliki ' . $activeBookings . ' booking aktif (Dibayar). Selesaikan atau batalkan booking terlebih dahulu.');
        }

        // Hapus booking non-aktif terkait kamar ini
        $room->bookings()->whereNotIn('status', ['Dibayar'])->delete();

        $roomNumber = $room->number;
        $room->delete();

        return redirect(route('dashboard.admin') . '#manajemen-kamar')
            ->with('success', 'Kamar ' . $roomNumber . ' berhasil dihapus beserta booking non-aktif terkait.');
    }
}