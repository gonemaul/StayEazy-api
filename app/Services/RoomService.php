<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomClass;
use Illuminate\Http\Request;
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
        // logika filter berdasarkan tanggal, kelas dll.
        // return Room::where(...)->get();
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
