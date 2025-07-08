<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'check_in',
        'check_out',
        'count_rooms',
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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
