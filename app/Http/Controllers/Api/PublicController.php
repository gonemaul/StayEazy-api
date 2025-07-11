<?php

namespace App\Http\Controllers\Api;

use App\Models\Hotel;
use App\Models\RoomClass;
use Illuminate\Http\Request;
use App\Services\CityService;
use App\Services\RoomService;
use App\Services\HotelService;
use App\Http\Controllers\Controller;

class PublicController extends Controller
{
    public function listHotel()
    {
        return HotelService::listHotels();
    }
    public function roomsByHotel(Hotel $hotel)
    {
        return HotelService::roomsByHotel($hotel);
    }
    public function roomDetail(Hotel $hotel, RoomClass $roomClass)
    {
        return HotelService::roomDetail($hotel, $roomClass);
    }
    public function roomAvailable(Request $request)
    {
        return RoomService::checkAvailability($request);
    }

    public function listCity()
    {
        return CityService::listCities();
    }
}
