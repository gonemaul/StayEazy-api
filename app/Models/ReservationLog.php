<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationLog extends Model
{
    protected $fillable = [
        'reservation_id',
        'performed_by',
        'action',
        'note',
        'performed_at'
    ];
    protected $casts = [
        'performed_at' => 'datetime'
    ];

    const ACTION_CHECKIN = 'checkin';
    const ACTION_CHECKOUT = 'checkout';
    const ACTION_CANCEL = 'cancel';
    const ACTION_CONFIRM = 'confirm';
    const ACTION_CREATE = 'create';
    const ACTIONS = [
        self::ACTION_CHECKIN,
        self::ACTION_CHECKOUT,
        self::ACTION_CANCEL,
        self::ACTION_CONFIRM,
        self::ACTION_CREATE,
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
