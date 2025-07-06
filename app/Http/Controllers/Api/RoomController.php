<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('hotel.city', 'roomClass')->get();
        return response()->json($rooms);
    }

    public function show($id)
    {
        $room = Room::with('hotel.city', 'roomClass')->findOrFail($id);
        return response()->json($room);
    }
}
