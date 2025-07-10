<?php

namespace App\Services;

use App\Models\User;
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Anda belum pernah melakukan reservasi']);
        }
    }

    public static function userReservationDetail($id)
    {
        try {
            $res = Reservation::with('room', 'room.hotel')->findOrFail($id);
            return $res;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Reservasi tidak ditemukan']);
        }
    }

    public static function storeReservation(Request $request, User $user)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
            'count_rooms' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request, $user) {
            // Cek ketersediaan
            $isBooked = Reservation::where('room_id', $request->room_id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($q) use ($request) {
                    $q->whereBetween('checkin_date', [$request->checkin_date, $request->checkout_date])
                        ->orWhereBetween('checkout_date', [$request->checkin_date, $request->checkout_date])
                        ->orWhere(function ($q2) use ($request) {
                            $q2->where('checkin_date', '<=', $request->checkin_date)
                                ->where('checkout_date', '>=', $request->checkout_date);
                        });
                })->exists();

            if ($isBooked) {
                throw new \Exception('Kamar tidak tersedia pada tanggal tersebut');
            }

            $reservation = Reservation::create([
                'user_id' => $user->id,
                'room_id' => $request->room_id,
                'checkin_date' => $request->checkin_date,
                'checkout_date' => $request->checkout_date,
                'status' => 'pending',

                'code' => strtoupper(Str::random(6)),
            ]);

            try {
                // Mail::to($user->email)->send(new \App\Mail\ReservationCreated($reservation));
            } catch (\Throwable $e) {
                // Log::error('Gagal kirim email: ' . $e->getMessage());
                // Tidak perlu throw ulang kalau tidak krusial
            }

            return $reservation;
        });
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