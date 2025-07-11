<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'user'              => $this->user ? [
                'id'            => $this->user->id,
                'name'          => $this->user->name,
                'email'         => $this->user->email,
            ] : null,
            'room_unit'         => $this->roomUnit ? [
                'id'            => $this->roomUnit->id,
                'room_number'   => $this->roomUnit->room_number,
                'room_class'    => $this->roomUnit->roomClass ? [
                    'id'        => $this->roomUnit->roomClass->id,
                    'name'      => $this->roomUnit->roomClass->name,
                    'price'     => 'Rp ' . number_format($this->roomUnit->roomClass->price, 0, ',', '.'),
                ] : null
            ] : null,
            'checkin_date'      => \Carbon\Carbon::parse($this->checkin_date)->format('d M Y H:i'),
            'checkout_date'     => \Carbon\Carbon::parse($this->checkout_date)->format('d M Y H:i'),
            'guest_count'       => $this->guest_count,
            'amount_price'      => 'Rp ' . number_format($this->amount_price, 0, ',', '.'),
            'status'            => $this->status,
            'code_reservation'  => $this->code_reservation,
            'payment_token'     => $this->payment_token,
        ];
    }
}
