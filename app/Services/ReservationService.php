<?php

namespace App\Services;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationService
{
    public static function listUserReservations(User $user)
    {
        return $user->reservations()->latest()->get();
    }

    public static function userReservationDetail($id)
    {
        return Reservation::with('room', 'hotel')->findOrFail($id);
    }

    public static function storeReservation(Request $request, User $user)
    {
        // return Reservation::create([...]);
    }

    public static function cancelReservation($id)
    {
        return Reservation::where('id', $id)->update(['status' => 'cancelled']);
    }

    public static function listAllReservations()
    {
        return Reservation::with('user', 'room')->get();
    }

    public static function updateReservationStatus($id, $status)
    {
        $res = Reservation::findOrFail($id);
        $res->status = $status;
        $res->save();
        return $res;
    }

    public static function listStaffReservations()
    {
        return Reservation::where('status', 'pending_checkin')->get();
    }
}
