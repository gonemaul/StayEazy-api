<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Room;
use App\Models\User;
use App\Models\Hotel;
use App\Models\RoomUnit;
use App\Models\RoomClass;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Services\CityService;
use App\Services\RoomService;
use App\Services\HotelService;
use App\Services\StaffService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;
use App\Mail\ReservationCancelledMail;
use App\Mail\ReservationConfirmedMail;

class AdminController extends Controller
{
    public function cities()
    {
        return CityService::listCities();
    }

    public function storeHotel(Request $request)
    {
        return HotelService::store($request);
    }

    public function updateHotel(Request $request, Hotel $hotel)
    {
        return HotelService::update($request, $hotel);
    }

    public function storeRoomClass(Request $request)
    {
        return RoomService::storeRoomClass($request);
    }

    public function updateRoomClass(Request $request, RoomClass $roomClass)
    {
        return RoomService::updateRoomClass($request, $roomClass);
    }

    public function storeRoom(Request $request)
    {
        return RoomService::storeRoom($request);
    }

    public function updateRoom(Request $request, RoomUnit $roomUnit)
    {
        return RoomService::updateRoom($request, $roomUnit);
    }

    public function listStaff(Request $request)
    {
        return StaffService::listStaffs();
    }

    public function createStaff(Request $request)
    {
        return StaffService::create($request);
    }
    public function updateStaff(Request $request, $staff)
    {
        return StaffService::update($request, $staff);
    }
    public function deleteStaff(Request $request, $staff)
    {
        return StaffService::delete($request, $staff);
    }

    public function notifications(Request $request)
    {
        return NotificationService::sendNotification($request);
    }

    public function allReservations()
    {
        return ReservationService::listAllReservations();
    }

    public function updateReservationStatus(Request $request, Reservation $reservation)
    {
        return ReservationService::updateReservationStatus($request, $reservation);
    }
}
