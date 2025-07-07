<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_class_id',
        'name',
        'unit',
        'capacity',
        'price_day',
        'description'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomClass()
    {
        return $this->belongsTo(RoomClass::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}