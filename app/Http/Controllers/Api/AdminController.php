<?php

namespace App\Http\Controllers\Api;

use App\Mail\ReservationCancelledMail;
use App\Models\City;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\RoomClass;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmedMail;
use Illuminate\Http\JsonResponse;

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
            'address' => 'required',
            'description' => 'nullable',
        ]);
        DB::beginTransaction();
        try {
            $hotel = Hotel::create($request->all());
            DB::commit();
            return response()->json(['message' => 'Hotel berhasil ditambahkan', 'data' => $hotel]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Hotel gagal ditambahkan']);
        }
    }

    public function storeRoomClass(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $class = RoomClass::create($request->all());
            DB::commit();
            return response()->json(['message' => 'Kelas kamar berhasil ditambahkan', 'data' => $class]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Kelas kamar gagal ditambahkan']);
        }
    }

    public function storeRoom(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_class_id' => 'required|exists:room_classes,id',
            'name' => 'required',
            'unit' => 'required|integer|min:1',
            'capacuty' => 'required|integer|min:1',
            'price_day' => 'required|numeric|min:0',
            'description' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $room = Room::create($request->all());
            DB::commit();
            return response()->json(['message' => 'Kamar berhasil ditambahkan', 'data' => $room]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Kamar gagal ditambahkan']);
        }
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

        DB::beginTransaction();
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->status = $request->status;
            $reservation->save();
            if ($reservation->status == 'confirmed') {
                Mail::to($reservation->user->email)->send(new ReservationConfirmedMail($reservation));
            } else if ($reservation->status == 'cancelled') {
                Mail::to($reservation->user->email)->send(new ReservationCancelledMail($reservation));
            }
            DB::commit();
            return response()->json(['message' => 'Status reservasi diperbarui', 'data' => $reservation]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Status reservasi gagal diperbarui', 'data' => $reservation, 'error' => $e->getMessage()]);
        }
    }
}
