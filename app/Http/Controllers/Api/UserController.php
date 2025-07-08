<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ReservationService;
use App\Services\NotificationService;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return ReservationService::listUserReservations($request->user());
    }

    public function show($id)
    {
        return ReservationService::userReservationDetail($id);
    }

    public function store(Request $request)
    {
        return ReservationService::storeReservation($request, $request->user());
    }

    public function cancel($id)
    {
        return ReservationService::cancelReservation($id);
    }

    public function notifications(Request $request)
    {
        return NotificationService::listUserNotifications($request->user());
    }
}
