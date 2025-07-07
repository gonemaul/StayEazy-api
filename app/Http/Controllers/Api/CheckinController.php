<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use App\Mail\CheckinOtpMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class CheckinController extends Controller
{
    // Langkah 1: Mulai check-in dan kirim OTP
    public function start(Request $request)
    {
        $request->validate([
            'code_reservation' => 'required'
        ]);

        $reservation = Reservation::where('code_reservation', $request->code_reservation)->firstOrFail();

        if ($reservation->status !== 'confirmed') {
            return response()->json(['message' => 'Reservasi tidak valid'], 422);
        }

        $otp = rand(100000, 999999);
        $reservation->otp_code = $otp;
        $reservation->otp_expired_at = now()->addMinutes(10);
        $reservation->save();

        Mail::to($reservation->user->email)->send(new CheckinOtpMail($reservation));

        return response()->json(['message' => 'OTP dikirim ke email user']);
    }

    // Langkah 2: Verifikasi OTP
    public function confirm(Request $request)
    {
        $request->validate([
            'code_reservation' => 'required',
            'otp' => 'required'
        ]);

        $reservation = Reservation::where('code_reservation', $request->code_reservation)->first();

        if (!$reservation || !$reservation->otp_code) {
            return response()->json(['message' => 'OTP tidak ditemukan'], 404);
        }

        if ($reservation->otp_code !== $request->otp) {
            return response()->json(['message' => 'OTP salah'], 401);
        }

        if (now()->gt($reservation->otp_expired_at)) {
            return response()->json(['message' => 'OTP sudah kedaluwarsa'], 410);
        }

        $reservation->status = 'expired';
        $reservation->otp_verified_at = now();
        $reservation->save();

        return response()->json(['message' => 'Check-in berhasil']);
    }
}
