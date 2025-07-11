<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Hotel;
use App\Models\RoomUnit;
use App\Models\RoomClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\HotelResource;
use App\Http\Resources\RoomUnitResource;
use App\Http\Resources\RoomClassResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\RoomClassDetailResource;

class HotelService
{
    public static function listHotels()
    {
        try {
            $hotels = Hotel::with('city')->get();
            if ($hotels->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data hotel masih kosong.',
                    'data' => [],
                    'errors' => null
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data hotel berhasil diambil',
                'data' => HotelResource::collection($hotels),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data hotel',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public static function roomsByHotel($hotel)
    {
        try {
            $rooms = $hotel->roomClasses()->with('roomUnits')->get();
            if ($rooms->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kelas kamar dari hotel ' . $hotel->name . ' masih kosong.',
                    'data' => [],
                    'errors' => null
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data kelas kamar dari hotel ' . $hotel->name . ' berhasil diambil',
                'data' => RoomClassResource::collection($rooms),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kelas kamar',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public static function roomDetail($hotel, $roomClass)
    {
        try {
            if ($roomClass->hotel_id !== $hotel->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamar tidak ditemukan di hotel ini',
                    'data' => null,
                    'errors' => null
                ], 404);
            }
            $roomClass->load('roomUnits');

            return response()->json([
                'success' => true,
                'message' => 'Detail kamar class ' . $roomClass->name . ', Hotel ' . $hotel->name . ' berhasil diambil',
                'data' => new RoomClassDetailResource($roomClass),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail kamar',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public static function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required',
            'description' => 'nullable',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sesuai.',
                'data' => null,
                'errors' => $validatedData->errors()
            ], 422);
        }

        try {
            $hotel = Hotel::create($validatedData->validated());
            return response()->json([
                'success' => true,
                'message' => 'Hotel berhasil ditambahkan',
                'data' => new HotelResource($hotel),
                'errors' => null
            ], 201);
        } catch (\Exception $e) {
            // DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hotel gagal ditambahkan',
                'data' => [],
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public static function update(Request $request, $hotel)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required',
            'description' => 'nullable',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sesuai.',
                'data' => null,
                'errors' => $validatedData->errors()
            ], 422);
        }

        try {
            $hotel->update($validatedData->validated());
            return response()->json([
                'success' => true,
                'message' => 'Hotel berhasil diperbarui',
                'data' => new HotelResource($hotel),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Hotel gagal diperbarui',
                'data' => [],
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
