<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MidtransController extends Controller
{
    public function handleCallback(Request $request)
    {
        // Log callback untuk debug
        Log::info('Midtrans Callback:', $request->all());

        $notif = new \Midtrans\Notification();

        $orderId = $notif->order_id;
        $transactionStatus = $notif->transaction_status;
        $paymentType = $notif->payment_type;
        $fraudStatus = $notif->fraud_status ?? null;

        $reservation = Reservation::where('code_reservation', $orderId)->first();

        if (!$reservation) {
            return response()->json(['message' => 'Reservasi tidak ditemukan'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
                if ($paymentType === 'credit_card') {
                    if ($fraudStatus === 'challenge') {
                        $reservation->status = Reservation::PENDING_PAYMENT;
                    } else {
                        $reservation->status = Reservation::PAID;
                    }
                }
                break;

            case 'settlement':
                $reservation->status = Reservation::PAID;
                break;

            case 'pending':
                $reservation->status = Reservation::PENDING_PAYMENT;
                break;

            case 'deny':
            case 'cancel':
                $reservation->status = Reservation::CANCELLED;
                break;

            case 'expire':
                $reservation->status = Reservation::PAYMENT_EXPIRED;
                break;

            default:
                // fallback
                $reservation->status = Reservation::PENDING_PAYMENT;
        }

        $reservation->save();

        return response()->json(['message' => 'Callback diproses']);
    }
}
