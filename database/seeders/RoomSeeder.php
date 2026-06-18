<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['number' => '101', 'type' => 'standard', 'price' => 1200000, 'floor' => 1, 'status' => 'available'],
            ['number' => '102', 'type' => 'standard', 'price' => 1200000, 'floor' => 1, 'status' => 'occupied'],
            ['number' => '103', 'type' => 'deluxe', 'price' => 1800000, 'floor' => 1, 'status' => 'available'],
            ['number' => '104', 'type' => 'deluxe', 'price' => 1800000, 'floor' => 1, 'status' => 'maintenance'],
            ['number' => '201', 'type' => 'standard', 'price' => 1300000, 'floor' => 2, 'status' => 'available'],
            ['number' => '202', 'type' => 'deluxe', 'price' => 1900000, 'floor' => 2, 'status' => 'occupied'],
            ['number' => '203', 'type' => 'premium', 'price' => 2500000, 'floor' => 2, 'status' => 'available'],
            ['number' => '204', 'type' => 'premium', 'price' => 2500000, 'floor' => 2, 'status' => 'occupied'],
            ['number' => '301', 'type' => 'premium', 'price' => 2800000, 'floor' => 3, 'status' => 'available'],
            ['number' => '302', 'type' => 'premium', 'price' => 3000000, 'floor' => 3, 'status' => 'available'],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(
                ['number' => $room['number']],
                $room
            );
        }
    }
}
