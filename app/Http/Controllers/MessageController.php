<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'message' => 'required|string|max:5000',
        ]);

        Message::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $validated['phone'],
            'message' => $validated['message'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.',
        ]);
    }

    public function markRead(Message $message)
    {
        $message->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return response()->json(['success' => true]);
    }
}