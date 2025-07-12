<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'price'     => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'capacity'  => $this->capacity,
            'hotel_id'  => $this->hotel_id,
        ];
    }
}
