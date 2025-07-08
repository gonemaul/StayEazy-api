<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Room;
use App\Models\Hotel;
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

    public function updateHotel(Request $request, $id)
    {
        return HotelService::update($request, $id);
    }

    public function storeRoomClass(Request $request)
    {
        return RoomService::storeRoomClass($request);
    }

    public function updateRoomClass(Request $request)
    {
        return RoomService::updateRoomClass($request);
    }

    public function storeRoom(Request $request)
    {
        return RoomService::storeRoom($request);
    }

    public function updateRoom(Request $request)
    {
        return RoomService::updateRoom($request);
    }

    public function createStaff(Request $request)
    {
        return StaffService::create($request);
    }
    public function updateStaff(Request $request, $id)
    {
        return StaffService::update($request, $id);
    }
    public function deleteStaff(Request $request, $id)
    {
        return StaffService::delete($request, $id);
    }

    public function notifications(Request $request)
    {
        return NotificationService::sendNotification($request);
    }

    public function allReservations()
    {
        return ReservationService::listAllReservations();
    }

    public function updateReservationStatus(Request $request, $id)
    {
        return ReservationService::updateReservationStatus($id, $request->status);
    }
}
