<?php

namespace App\Http\Controllers;

use App\Models\Room;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::where('status', '!=', 'maintenance')
            ->where('slots', '>', 0)
            ->orderBy('number')
            ->get();

        return view('index', compact('rooms'));
    }
}