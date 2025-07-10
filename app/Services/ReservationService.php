<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\RoomUnit;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public static function listUserReservations(User $user)
    {
        try {
            $res = $user->reservations()->latest()->get();
            if ($res->isEmpty()) {
                return response()->json(['message' => 'Anda belum pernah melakukan reservasi']);
            }
            return $res;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Reservasi gagal ditemukan.']);
        }
    }

    public static function userReservationDetail($id)
    {
        try {
            $res = Reservation::with('roomUnit')->findOrFail($id);
            return $res;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Reservasi tidak ditemukan']);
        }
    }

    public static function storeReservation(Request $request)
    {
        $request->validate([
            'room_class_id' => 'required|exists:room_classes,id',
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
            'quantity' => 'required|integer|min:1',
            'guest_count' => 'nullable|integer|min:1'
        ]);
        $userId = auth()->id();

        DB::beginTransaction();

        try {
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
                    'data' => []
                ], 422);
            }

            $created = [];
            $checkin = Carbon::parse($request->checkin_date);
            $checkout = Carbon::parse($request->checkout_date);
            $nights = $checkin->diffInDays($checkout); // jumlah malam

            foreach ($availableUnits as $unit) {
                $pricePerNight = $unit->roomClass->price;
                $amount = $nights * $pricePerNight;

                $created[] = Reservation::create([
                    'user_id' => $userId,
                    'room_unit_id' => $unit->id,
                    'checkin_date' => $request->checkin_date,
                    'checkout_date' => $request->checkout_date,
                    'guest_count' => $request->guest_count ?? 1,
                    'amount_price' => $amount,
                    'code_reservation' => strtoupper(Str::random(6)),
                    'status' => Reservation::PENDING
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibuat.',
                'data' => $created,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan reservasi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public static function cancelReservation($id, User $user)
    {
        $reservation = Reservation::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        if ($reservation->status !== 'pending') {
            abort(403, 'Reservasi tidak bisa dibatalkan');
        }


        return DB::transaction(function () use ($reservation) {
            $reservation->status = 'cancelled';
            $reservation->save();
            return $reservation;
            // Mail::to($user->email)->send(new \App\Mail\ReservationCancelled($reservation));
        });
    }

    public static function listAllReservations()
    {
        return Reservation::with('user', 'room')->get();
    }

    public static function updateReservationStatus($id, $status)
    {
        return DB::transaction(function () use ($id, $status) {
            $res = Reservation::findOrFail($id);
            $res->status = $status;
            $res->save();
            return $res;
        });
    }

    public static function listStaffReservations(User $staff)
    {
        return Reservation::whereHas('room', function ($q) use ($staff) {
            $q->where('hotel_id', $staff->hotel_id);
        })->with(['room', 'user'])->get();
    }
}
