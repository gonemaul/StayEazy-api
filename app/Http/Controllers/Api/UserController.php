<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Services\NotificationService;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return ReservationService::listUserReservations(auth()->user());
    }

    public function show($id)
    {
        return ReservationService::userReservationDetail($id);
    }

    public function create(Request $request)
    {
        return ReservationService::storeReservation($request);
    }

    public function cancel(Reservation $reservation)
    {
        return ReservationService::cancelReservation($reservation);
    }

    public function notifications(Request $request)
    {
        return NotificationService::listUserNotifications(auth()->user());
    }
}
