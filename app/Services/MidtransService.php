<?php

namespace App\Services;

use App\Models\Reservation;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public static function createSnapToken(Reservation $reservation): string
    {
        self::config();

        $params = [
            'transaction_details' => [
                'order_id' => $reservation->code_reservation,
                'gross_amount' => $reservation->amount_price,
            ],
            'customer_details' => [
                'first_name' => $reservation->user->name,
                'email' => $reservation->user->email,
            ],
            'callbacks' => [
                'finish' => route('midtrans.callback'),
            ],
        ];

        return Snap::getSnapToken($params);
    }

    private static function config(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}
