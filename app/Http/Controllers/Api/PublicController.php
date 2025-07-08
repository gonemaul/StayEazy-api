<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\RoomService;
use App\Services\HotelService;
use App\Http\Controllers\Controller;

class PublicController extends Controller
{
    public function index()
    {
        return RoomService::listRooms();
    }

    public function show($id)
    {
        return RoomService::roomDetail($id);
    }

    public function rooms($hotelId)
    {
        return HotelService::roomsByHotel($hotelId);
    }

    public function roomDetail($hotelId, $rid)
    {
        return HotelService::roomDetail($hotelId, $rid);
    }
}
