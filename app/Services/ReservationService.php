<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\RoomUnit;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\ReservationDetailResource;

class ReservationService
{
    public static function listUserReservations(User $user)
    {
        try {
            $res = $user->reservations()->with(['roomUnit.roomClass'])->latest()->get();
            if ($res->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Anda belum pernah melakukan reservasi.',
                    'data' => [],
                    'errors' => null
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data reservasi anda berhasil diambil.',
                'data' => ReservationResource::collection($res),
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data reservasi anda.',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public static function userReservationDetail($id)
    {
        try {
            $res = Reservation::with(['roomUnit.roomClass'])->findOrFail($id);
            if ($res->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak diizinkan melihat reservasi ini.',
                    'data' => [],
                    'errors' => null
                ], 403);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data reservasi anda berhasil diambil.',
                'data' => new ReservationDetailResource($res),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data reservasi anda.',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public static function storeReservation(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'room_class_id' => 'required|exists:room_classes,id',
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
            'quantity' => 'required|integer|min:1',
            'guest_count' => 'nullable|integer|min:1'
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
            $userId = auth()->id();
            // Ambil unit kamar dari class
            $units = RoomUnit::with('roomClass')->where('room_class_id', $request->room_class_id)
                ->where('status', 'available')
                ->get();

            // Filter unit yang tidak bentrok dengan booking lain
            $availableUnits = $units->filter(function ($unit) use ($request) {
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
            })->take($request->quantity);

            if ($availableUnits->count() < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamar yang tersedia tidak mencukupi.',
                    'data' => [],
                    'errors' => [
                        'room' => 'Kamar tidak tersedia.'
                    ]
                ], 422);
            }

            $created = [];
            $checkin = Carbon::parse($request->checkin_date)->setTime(13, 0);  // jam 13:00
            $checkout = Carbon::parse($request->checkout_date)->setTime(12, 0); // jam 12:00
            $nights = $checkin->diffInDays($checkout); // jumlah malam

            foreach ($availableUnits as $unit) {
                $pricePerNight = $unit->roomClass->price;
                $amount = $nights * $pricePerNight;
                $code = 'RS-' . Carbon::now()->format('dm') . // e.g., 1007
                    '-U' . $userId .
                    'H' . $unit->roomClass->hotel_id .
                    '-C' . $unit->roomClass->id .
                    'R' . $unit->id .
                    '-' . strtoupper(Str::random(4));

                $created[] = Reservation::create([
                    'user_id' => $userId,
                    'room_unit_id' => $unit->id,
                    'checkin_date' => $checkin,
                    'checkout_date' => $checkout,
                    'guest_count' => $request->guest_count ?? 1,
                    'amount_price' => $amount,
                    'code_reservation' => $code,
                    'status' => Reservation::PENDING
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibuat.',
                'data' => ReservationResource::collection($created),
                'errors' => null
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan reservasi.',
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public static function updateReservationStatus(Request $request, $reservation)
    {
        $validatedData = Validator::make($request->all(), [
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
            $reservation->status = Reservation::CANCELLED;
            $reservation->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Status reservasi berhasil diperbarui.',
                'data' => new ReservationDetailResource($reservation),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pembaruan status reservasi.',
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public static function cancelReservation($reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu tidak diizinkan membatalkan reservasi ini.',
                'data' => [],
                'errors' => null
            ], 403);
        }

        if ($reservation->status !== Reservation::PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak bisa dibatalkan karena status saat ini bukan pending.',
                'data' => [],
                'errors' => null
            ], 409);
        }

        DB::beginTransaction();
        try {
            $reservation->status = Reservation::CANCELLED;
            $reservation->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibatalkan.',
                'data' => new ReservationDetailResource($reservation),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pembatalan reservasi.',
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public static function listAllReservations()
    {
        try {
            $res = Reservation::with(['user', 'roomUnit'])->latest()->get();
            if ($res->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Belum ada reservasi.',
                    'data' => [],
                    'errors' => null
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data reservasi berhasil diambil.',
                'data' => ReservationDetailResource::collection($res),
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data reservasi.',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }


    public static function listStaffReservations()
    {
        try {
            $staff = Auth::user();
            $res = Reservation::whereHas('roomUnit', function ($q) use ($staff) {
                $q->where('hotel_id', $staff->hotel_id);
            })->with(['roomUnit', 'user'])->get();

            if ($res->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Belum ada reservasi.',
                    'data' => [],
                    'errors' => null
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data reservasi hotel anda berhasil diambil.',
                'data' => ReservationResource::collection($res),
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data reservasi hotel anda.',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
