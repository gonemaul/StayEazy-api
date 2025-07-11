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
        'checkin_date' => 'datetime',
        'checkout_date' => 'datetime',
    ];

    const PENDING_PAYMENT = 'pending_payment';
    const PAYMENT_EXPIRED = 'payment_expired';
    const PAID = 'paid';
    const CHECKED_IN = 'checked_in';
    const CHECKED_OUT = 'checked_out';
    const CHECKED_OUT_OVERDUE = 'checked_out_overdue';
    const PARTIAL_PAID = 'partial_paid';
    const CANCELLED = 'cancelled';

    const STATUSES = [
        self::PENDING_PAYMENT,
        self::PAYMENT_EXPIRED,
        self::PAID,
        self::CHECKED_IN,
        self::CHECKED_OUT,
        self::CHECKED_OUT_OVERDUE,
        self::PARTIAL_PAID,
        self::CANCELLED
    ];

    public function getStatusLabel()
    {
        return match ($this->status) {
            self::PENDING_PAYMENT => 'Menunggu Pembayaran',
            self::PAID => 'Sudah Dibayar',
            self::CHECKED_IN => 'Check-in',
            self::CHECKED_OUT => 'Selesai',
            self::CHECKED_OUT_OVERDUE => 'Telat Check-out',
            self::CANCELLED => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roomUnit()
    {
        return $this->belongsTo(RoomUnit::class);
    }

    public function logs()
    {
        return $this->hasMany(ReservationLog::class);
    }
}
