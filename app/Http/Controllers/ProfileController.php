<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:20',
            'identity_number' => 'required|string|max:30',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:100',
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $user->tenantProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'],
                'identity_number' => $validated['identity_number'],
                'birth_date' => $validated['birth_date'],
                'birth_place' => $validated['birth_place'],
                'address' => $validated['address'],
                'occupation' => $validated['occupation'],
                'parent_name' => $validated['parent_name'],
                'parent_phone' => $validated['parent_phone'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data pribadi berhasil disimpan!',
        ]);
    }
}
