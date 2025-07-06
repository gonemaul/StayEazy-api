<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\RoomClass;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function cities()
    {
        return City::all();
    }

    public function storeHotel(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'alamat' => 'required',
            'deskripsi' => 'nullable',
        ]);

        $hotel = Hotel::create($request->all());

        return response()->json(['message' => 'Hotel berhasil ditambahkan', 'data' => $hotel]);
    }

    public function storeRoomClass(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'deskripsi' => 'nullable',
        ]);

        $class = RoomClass::create($request->all());

        return response()->json(['message' => 'Kelas kamar berhasil ditambahkan', 'data' => $class]);
    }

    public function storeRoom(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_class_id' => 'required|exists:room_classes,id',
            'nama_kamar' => 'required',
            'jumlah_unit' => 'required|integer|min:1',
            'kapasitas' => 'required|integer|min:1',
            'harga_per_malam' => 'required|numeric|min:0',
            'deskripsi' => 'nullable',
        ]);

        $room = Room::create($request->all());

        return response()->json(['message' => 'Kamar berhasil ditambahkan', 'data' => $room]);
    }

    public function allReservations()
    {
        return Reservation::with('room.hotel.city', 'user')->orderBy('created_at', 'desc')->get();
    }

    public function updateReservationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,expired'
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->status = $request->status;
        $reservation->save();

        return response()->json(['message' => 'Status reservasi diperbarui', 'data' => $reservation]);
    }
}
