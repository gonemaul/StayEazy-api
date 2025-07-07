<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = $request->user()->reservations()->with('room.hotel.city')->get();
        return response()->json($reservations);
    }

    public function show(Request $request, $id)
    {
        $reservation = $request->user()->reservations()->with('room.hotel.city')->findOrFail($id);
        return response()->json($reservation);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'count_rooms' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($data['room_id']);

        if ($data['count_rooms'] > $room->jumlah_unit) {
            return response()->json(['message' => 'Jumlah kamar melebihi stok.'], 422);
        }

        $lama_menginap = now()->parse($data['check_in'])->diffInDays(now()->parse($data['check_out']));
        $total_harga = $lama_menginap * $room->harga_per_malam * $data['count_rooms'];

        $reservation = Reservation::create([
            'user_id' => $request->user()->id,
            'room_id' => $room->id,
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'count_rooms' => $data['count_rooms'],
            'status' => 'pending',
            'amout_price' => $total_harga,
            'code_reservation' => strtoupper(Str::random(10)),
        ]);

        return response()->json([
            'message' => 'Reservasi berhasil dibuat',
            'data' => $reservation
        ], 201);
    }
}
