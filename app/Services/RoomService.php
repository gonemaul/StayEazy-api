<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomClass;
use App\Models\Reservation;
use App\Models\RoomUnit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoomService
{
    public static function listRooms()
    {
        return RoomUnit::with('hotel', 'roomClass')->get();
    }

    public static function roomDetail($id)
    {
        return RoomUnit::with('hotel', 'roomClass')->findOrFail($id);
    }

    public static function checkAvailability(Request $request)
    {
        $request->validate([
            'room_class_id' => 'required|exists:room_classes,id',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'quantity' => 'required|integer|min:1',
        ]);

        $units = RoomUnit::with('reservations')
            ->where('room_class_id', $request->room_class_id)
            ->where('status', RoomUnit::AVAILABLE)
            ->get();

        $available = $units->filter(function ($unit) use ($request) {
            return !$unit->reservations()->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->whereBetween('checkin_date', [$request->checkin_date, $request->checkout_date])
                        ->orWhereBetween('checkout_date', [$request->checkin_date, $request->checkout_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('checkin_date', '<=', $request->checkin_date)
                                ->where('checkout_date', '>=', $request->checkout_date);
                        });
                });
            })->exists();
        })->take($request->quantity)->values();

        if ($available->count() < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah kamar tidak mencukupi untuk tanggal tersebut.',
                'data' => [],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kamar tersedia.',
            'data' => [
                'id' => $available,
                // 'room_class_id' => $available->room_class_id,
                // 'room_number' => $available->room_number,
            ]
        ]);
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
            $room = RoomUnit::create($request->all());
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
            $room = RoomUnit::create($request->all());
            DB::commit();
            return response()->json(['message' => 'Kamar berhasil ditambahkan', 'data' => $room]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Kamar gagal ditambahkan']);
        }
    }
}