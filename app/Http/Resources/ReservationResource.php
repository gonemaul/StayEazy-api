<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'checkin_date'          => \Carbon\Carbon::parse($this->checkin_date)->format('d M Y H:i'),
            'checkout_date'         => \Carbon\Carbon::parse($this->checkout_date)->format('d M Y H:i'),
            'guest_count'           => $this->guest_count,
            'amount_price'          => 'Rp ' . number_format($this->amount_price, 0, ',', '.'),
            'status'                => $this->status,
            'code_reservation'      => $this->code_reservation,
            'payment_token'         => $this->payment_token,
            'link_payment'          => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $this->payment_token
        ];
    }
}
