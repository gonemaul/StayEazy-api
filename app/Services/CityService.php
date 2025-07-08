<?php

namespace App\Services;

use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityService
{
    public static function listCities()
    {
        return City::all();
    }
}
