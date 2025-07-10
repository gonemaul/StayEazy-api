<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomClass;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoomService
{
    public static function listRooms()
    {
        return Room::with('hotel', 'roomClass')->get();
    }

    public static function roomDetail($id)
    {
        return Room::with('hotel', 'roomClass')->findOrFail($id);
    }

    public static function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'hotel_id' => 'nullable|exists:hotels,id',
            'room_class_id' => 'nullable|exists:room_classes,id',
            'guest_count' => 'nullable|integer|min:1'
        ]);

        $checkin = Carbon::parse($validated['check_in']);
        $checkout = Carbon::parse($validated['check_out']);

        $query = Room::with('hotel', 'roomClass');

        if (!empty($validated['hotel_id'])) {
            $query->where('hotel_id', $validated['hotel_id']);
        }

        if (!empty($validated['room_class_id'])) {
            $query->where('room_class_id', $validated['room_class_id']);
        }

        // Guest count tidak dipakai filter jumlah room di sini, tapi bisa dipakai nanti saat booking

        $availableRooms = $query->whereDoesntHave('reservations', function ($q) use ($checkin, $checkout) {
            $q->where(function ($query) use ($checkin, $checkout) {
                $query->whereBetween('check_in', [$checkin, $checkout->copy()->subDay()])
                    ->orWhereBetween('check_out', [$checkin->copy()->addDay(), $checkout])
                    ->orWhere(function ($q2) use ($checkin, $checkout) {
                        $q2->where('check_in', '<=', $checkin)
                            ->where('check_out', '>=', $checkout);
                    });
            })->whereNotIn('status', ['cancelled']);
        })->get();

        return $availableRooms;
    }
    public static function storeRoomClass(Request $request)
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

    public static function updateRoomClass(Request $request)
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

    public static function storeRoom(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_class_id' => 'required|exists:room_classes,id',
            'name' => 'required',
            'unit' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
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
    public static function updateRoom(Request $request)
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
}
