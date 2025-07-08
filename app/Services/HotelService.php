<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelService
{
    public static function listHotels()
    {
        return Hotel::with('city')->get();
    }

    public static function roomsByHotel($hotelId)
    {
        return Room::where('hotel_id', $hotelId)->get();
    }

    public static function roomDetail($hotelId, $roomId)
    {
        return Room::where('hotel_id', $hotelId)->findOrFail($roomId);
    }

    public static function store(Request $request)
    {
        $validatedData = $request->validate(rules: [
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required',
            'description' => 'nullable',
        ]);
        // DB::beginTransaction();
        // try {
        $hotel = Hotel::create($validatedData);
        // DB::commit();
        return response()->json(['message' => 'Hotel berhasil ditambahkan', 'data' => $hotel]);
        // } catch (\Exception $e) {
        // DB::rollBack();
        // return response()->json(['message' => 'Hotel gagal ditambahkan']);
        // }
    }

    public static function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required',
            'description' => 'nullable',
        ]);
        // DB::beginTransaction();
        // try {
        $hotel = Hotel::findOrFail($id);
        if ($hotel) {
            $hotel->update($validatedData);
        }
        // DB::commit();
        return response()->json(['message' => 'Hotel berhasil diperbarui', 'data' => $hotel]);
        // } catch (\Exception $e) {
        // DB::rollBack();
        // return response()->json(['message' => 'Hotel gagal diperbarui']);
        // }
    }
}
