<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'updated_at'    => \Carbon\Carbon::parse($this->updated_at)->format('d M Y H:i'),
            'hotel'         => new HotelResource($this->whenLoaded('hotel'))
        ];
    }
}
