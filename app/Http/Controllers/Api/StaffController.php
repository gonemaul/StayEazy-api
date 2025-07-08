<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CheckinService;
use App\Http\Controllers\Controller;
use App\Services\ReservationService;

class StaffController extends Controller
{
    public function reservations()
    {
        return ReservationService::listStaffReservations();
    }

    public function start(Request $request)
    {
        return CheckinService::startCheckin($request);
    }

    public function confirm(Request $request)
    {
        return CheckinService::verifyCheckinCode($request);
    }

    public function checkout(Request $request)
    {
        return CheckinService::checkout($request);
    }
}
