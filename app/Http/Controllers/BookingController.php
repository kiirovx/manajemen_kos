<?php

namespace App\Http\Controllers;

class BookingController extends Controller
{
    public function index()
    {
        return view('booking.booking');
    }
}
