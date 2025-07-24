<?php

namespace App\Http\Controllers\Api;

use Midtrans\Config;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
    }

    public function handleCallback(Request $request)
    {
        Log::info('Midtrans Callback Received:', $request->all());

        try {
            $notif = new \Midtrans\Notification();

            $orderId = $notif->order_id;
            $transactionStatus = $notif->transaction_status;
            $paymentType = $notif->payment_type;
            $fraudStatus = $notif->fraud_status ?? null;

            $reservation = Reservation::where('code_reservation', $orderId)->first();

            if (!$reservation) {
                Log::warning("Reservasi dengan kode $orderId tidak ditemukan.");
                return response()->json(['message' => 'Reservasi tidak ditemukan'], 404);
            }

            // Jika status sudah terminal, abaikan update
            if (in_array($reservation->status, [
                Reservation::CANCELLED,
                Reservation::PAYMENT_EXPIRED,
                Reservation::CHECKED_IN,
                Reservation::CHECKED_OUT
            ])) {
                Log::info("Status reservasi $orderId sudah final: {$reservation->status}. Tidak diubah.");
                return response()->json(['message' => 'Status reservasi sudah final'], 200);
            }

            switch ($transactionStatus) {
                case 'capture':
                    if ($paymentType === 'credit_card') {
                        $reservation->status = $fraudStatus === 'challenge'
                            ? Reservation::PENDING_PAYMENT
                            : Reservation::PAID;
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
                    Log::info("Transaksi $orderId dalam status tidak dikenali: $transactionStatus");
                    $reservation->status = Reservation::PENDING_PAYMENT;
            }

            $reservation->save();

            return response()->json(['message' => 'Callback diproses'], 200);
        } catch (\Exception $e) {
            Log::error('Gagal memproses callback Midtrans: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }
}
