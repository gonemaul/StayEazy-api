<?php

namespace App\Services;

use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityService
{
    public static function listCities()
    {
        try {
            $cities = City::all();
            if ($cities->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kota masih kosong.',
                    'data' => [],
                    'errors' => null
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data kota berhasil diambil',
                'data' => CityResource::collection($cities),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
