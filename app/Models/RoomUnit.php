<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_class_id',
        'room_number',
        'status'
    ];

    const AVAILABLE = 'available';
    const MAINTENANCE = 'maintenance';
    const STATUSES = [
        self::AVAILABLE,
        self::MAINTENANCE
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
