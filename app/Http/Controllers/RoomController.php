<?php

namespace App\Http\Controllers;

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
        ]);

        Room::create($validated + ['status' => 'available']);

        return redirect(route('dashboard.admin') . '#manajemen-kamar')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }
}
