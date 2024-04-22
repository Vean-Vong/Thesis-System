<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room = new Room();
        $room->name = 'A1';
        $room->save();

        $room = new Room();
        $room->name = 'A2';
        $room->save();

        $room = new Room();
        $room->name = 'A3';
        $room->save();

        $room = new Room();
        $room->name = 'A4';
        $room->save();

        $room = new Room();
        $room->name = 'A5';
        $room->save();

        $room = new Room();
        $room->name = 'A6';
        $room->save();
    }
}
