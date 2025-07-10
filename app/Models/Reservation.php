<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_unit_id',
        'checkin_date',
        'checkout_date',
        'guest_count',
        'amount_price',
        'status',
        'code_reservation',
        'payment_token'
    ];

    protected $casts = [
        'checkin_date' => 'date',
        'checkout_date' => 'date',
    ];

    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const CHECKED_IN = 'checked_in';
    const CHECKED_OUT = 'checked_out';
    const CANCELLED = 'cancelled';

    const STATUSES = [
        self::PENDING,
        self::CONFIRMED,
        self::CHECKED_IN,
        self::CHECKED_OUT,
        self::CANCELLED
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roomUnit()
    {
        return $this->belongsTo(RoomUnit::class);
    }
}