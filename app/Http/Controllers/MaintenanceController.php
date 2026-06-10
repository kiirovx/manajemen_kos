<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'priority' => 'required|string|max:50',
        ]);

        $maintenance = MaintenanceRequest::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Maintenance',
            'description' => "Ajukan perbaikan: {$maintenance->title}",
            'status' => 'Pending',
            'activity_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance berhasil diajukan! Tim kami akan segera menghubungi Anda.',
        ]);
    }
}
