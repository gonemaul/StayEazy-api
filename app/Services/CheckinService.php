<?php

namespace App\Services;

use Illuminate\Http\Request;

class CheckinService
{
    public static function startCheckin(Request $request)
    {
        // logika mulai checkin, kirim kode ke email
    }

    public static function verifyCheckinCode(Request $request)
    {
        // verifikasi kode dari user saat checkin
    }

    public static function checkout(Request $request)
    {
        // proses checkout
    }
}
