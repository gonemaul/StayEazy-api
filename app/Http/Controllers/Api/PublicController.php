<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\RoomService;
use App\Services\HotelService;
use App\Http\Controllers\Controller;

class PublicController extends Controller
{
    public function listHotel()
    {
        return HotelService::listHotels();
    }
    public function roomsByHotel($hotelId)
    {
        return HotelService::roomsByHotel($hotelId);
    }
    public function roomDetail($hotelId, $rId)
    {
        return HotelService::roomDetail($hotelId, $rId);
    }
    public function roomAvailable(Request $request)
    {
        return RoomService::checkAvailability($request);
    }
}