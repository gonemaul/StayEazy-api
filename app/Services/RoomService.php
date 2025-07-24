<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\RoomUnit;
use App\Models\RoomClass;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoomUnitResource;
use App\Http\Resources\RoomClassResource;
use Illuminate\Support\Facades\Validator;

class RoomService
{
    public static function checkAvailability(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'room_class_id' => 'required|exists:room_classes,id',
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => null,
                'errors' => $validatedData->errors()
            ], 422);
        }

        $checkinDate = Carbon::parse($request->checkin_date);
        $now = now();
        $cutoffTime = Carbon::today()->setTime(11, 0);
        if ($checkinDate->isToday() && $now->gt($cutoffTime)) {
            return response()->json([
                'success' => false,
                'message' => "Tidak bisa membuat reservasi untuk hari ini",
                'data' => [],
                'errors' => [
                    'check_in_date' => ['Reservasi untuk hari ini hanya diperbolehkan sebelum pukul 11:00.']
                ]
            ], 422);
        }

        try {
            $units = RoomUnit::with('reservations')
                ->where('room_class_id', $request->room_class_id)
                ->where('status', RoomUnit::AVAILABLE)
                ->get();

            $available = $units->filter(function ($unit) use ($request) {
                return !$unit->reservations()
                    ->where('status', '!=', Reservation::CANCELLED) // hanya cek yang aktif
                    ->where(function ($query) use ($request) {
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
                    'errors' => [
                        'quantity' => 'Jumlah kamar tidak mencukupi untuk tanggal tersebut.'
                    ]
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ketersediaan kamar berhasil diperiksa',
                'data' => RoomUnitResource::collection($available),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek ketersediaan kamar',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public static function storeRoomClass(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required',
            'price' => 'required|integer|min:1000|max:10000000',
            'capacity' => 'required|min:1|max:10|integer',
            'description' => 'required|max:250',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sesuai.',
                'data' => null,
                'errors' => $validatedData->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $class = RoomClass::create($validatedData->validated());
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Kelas kamar berhasil ditambahkan',
                'data' => new RoomClassResource($class),
                'errors' => null
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Kelas kamar gagal ditambahkan',
                'data' => [],
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public static function updateRoomClass(Request $request, $class)
    {
        $validatedData = Validator::make($request->all(), [
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required',
            'price' => 'required|integer|min:1000|max:10000000',
            'capacity' => 'required|min:1|max:10|integer',
            'description' => 'required|max:250',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sesuai.',
                'data' => null,
                'errors' => $validatedData->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $class->update($validatedData->validated());
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Kelas kamar berhasil diperbarui',
                'data' => new RoomClassResource($class),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => true,
                'message' => 'Kelas kamar gagal diperbarui',
                'data' => [],
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public static function storeRoom(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'room_class_id' => 'required|exists:room_classes,id',
            'room_number' => 'required|unique:room_units,room_number',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sesuai.',
                'data' => null,
                'errors' => $validatedData->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $room = RoomUnit::create([
                'room_class_id' => $request->room_class_id,
                'room_number' => $request->room_number,
                'status' => RoomUnit::AVAILABLE
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Kamar berhasil ditambahkan',
                'data' => new RoomUnitResource($room),
                'errors' => null
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Kamar gagal ditambahkan',
                'data' => [],
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public static function updateRoom(Request $request, $roomUnit)
    {
        $validatedData = Validator::make($request->all(), [
            'room_class_id' => 'required|exists:room_classes,id',
            'room_number' => 'required|unique:room_units,room_number',
            'status' => ['required', Rule::in(RoomUnit::STATUSES)],
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sesuai.',
                'data' => null,
                'errors' => $validatedData->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $roomUnit->update($validatedData->validated());
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Kamar berhasil diperbarui',
                'data' => new RoomUnitResource($roomUnit),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => true,
                'message' => 'Kamar gagal diperbarui',
                'data' => [],
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
