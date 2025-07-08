<?php

namespace App\Services;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CheckinService
{
    public static function startCheckin(Request $request, User $staff)
    {
        $reservation = Reservation::with('room')->findOrFail($request->reservation_id);

        if ($reservation->room->hotel_id !== $staff->hotel_id) {
            abort(403, 'Anda tidak memiliki izin untuk check-in reservasi ini');
        }
    }

    public static function verifyCheckinCode(Request $request, User $staff)
    {
        $reservation = Reservation::with('room')->findOrFail($request->reservation_id);

        if ($reservation->room->hotel_id !== $staff->hotel_id) {
            abort(403, 'Reservasi bukan dari hotel Anda');
        }
    }

    public static function checkout(Request $request, User $staff)
    {
        $reservation = Reservation::with('room')->findOrFail($request->reservation_id);

        if ($reservation->room->hotel_id !== $staff->hotel_id) {
            abort(403, 'Reservasi bukan dari hotel Anda');
        }
    }
}