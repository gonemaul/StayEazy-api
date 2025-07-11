<?php

namespace App\Services;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ReservationLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReservationResource;

class CheckinService
{
    public static function startCheckin(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'code_reservation' => 'required|string|exists:reservations,code_reservation',
            'guest_email' => 'required|email|exist:users,email',
            'guest_name' => 'required|string',
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
            $staff = Auth::user();

            $reservation = Reservation::where('code_reservation', $request->code_reservation)
                ->whereHas('roomUnit.roomClass.hotel', function ($q) use ($staff) {
                    $q->where('id', $staff->hotel_id);
                })
                ->with('user') // ambil data user untuk pengecekan email
                ->first();

            $now = now();
            $checkinTime = Carbon::parse($reservation->checkin_date);
            if ($now->lt($checkinTime->setTime(13, 0))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Check-in hanya bisa dilakukan setelah jam 13:00',
                    'data' => null,
                    'errors' => 'Minimal check-in jam 13.00 WIB.'
                ], 409);
            }

            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservasi tidak ditemukan untuk hotel Anda',
                    'data' => null,
                    'errors' => 'ID reservasi tidak valid'
                ], 404);
            }

            if ($reservation->user->email !== $request->guest_email || $reservation->user->name !== $request->guest_name) {
                return response()->json([
                    'success' => false,
                    'message' => 'Identitas tamu tidak cocok dengan reservasi ini.',
                    'data' => null,
                    'errors' => 'Identitas tamu dan reservasi tidak cocok'
                ], 403);
            }

            if (!in_array($reservation->status, [Reservation::PENDING, Reservation::CONFIRMED])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservasi tidak dapat di-check-in karena statusnya ' . $reservation->status,
                    'data' => null,
                    'errors' => 'Reservasi hanya bisa di-check-in jika statusnya Pending atau Confirm'
                ], 409);
            }

            $reservation->status = Reservation::CHECKED_IN;
            $reservation->save();

            ReservationLog::create([
                'reservation_id' => $reservation->id,
                'performed_by' => $staff->id,
                'action' => ReservationLog::ACTION_CHECKIN,
                'note' => 'Tamu melakukan check-in',
                'performed_at' => now(),
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Tamu berhasil check-in',
                'data' => new ReservationResource($reservation),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan reservasi.',
                'data' => null,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public static function checkout(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'code_reservation' => 'required|string|exists:reservations,code_reservation',
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
            $staff = Auth::user();

            $reservation = Reservation::where('code_reservation', $request->code_reservation)
                ->whereHas('roomUnit.roomClass.hotel', fn($q) => $q->where('id', $staff->hotel_id))
                ->first();

            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservasi tidak ditemukan atau bukan milik hotel Anda',
                    'data' => null,
                    'errors' => 'ID reservasi tidak valid.'
                ], 404);
            }

            if ($reservation->status !== Reservation::CHECKED_IN) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservasi tidak dapat di-check-out karena statusnya bukan checked_in',
                    'data' => null,
                    'errors' => 'Reservasi belum check-in.'
                ], 409);
            }

            $now = now();
            $expectedCheckout = Carbon::parse($reservation->checkout_date)->setTimeFromTimeString(config('reservation.checkout_time', '12:00'));

            $lateHours = $now->gt($expectedCheckout)
                ? ceil($expectedCheckout->diffInMinutes($now) / 60)
                : 0;

            $extraFee = 0;

            if ($lateHours > 0) {
                $perHour = config('reservation.late_fee_per_hour', 50000);
                $maxHour = config('reservation.max_hour_before_full_day', 6);

                if ($lateHours >= $maxHour) {
                    $extraFee = $reservation->roomUnit->roomClass->price_day;
                } else {
                    $extraFee = $lateHours * $perHour;
                }

                // Tambahkan ke total
                $reservation->amount_price += $extraFee;
            }

            $reservation->status = Reservation::CHECKED_OUT;
            $reservation->save();

            // Log aktivitas
            ReservationLog::create([
                'reservation_id' => $reservation->id,
                'performed_by' => $staff->id,
                'action' => ReservationLog::ACTION_CHECKOUT,
                'note' => $lateHours > 0
                    ? "Tamu checkout terlambat {$lateHours} jam. Biaya tambahan: Rp " . number_format($extraFee, 0, ',', '.')
                    : 'Tamu checkout tepat waktu',
                'performed_at' => now(),
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil' . ($lateHours > 0 ? " dengan biaya tambahan Rp " . number_format($extraFee, 0, ',', '.') : ''),
                'data' => new ReservationResource($reservation),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan reservasi.',
                'data' => null,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
