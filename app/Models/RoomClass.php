<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'price',
        'capacity',
        'description'
    ];

    public function roomUnits()
    {
        return $this->hasMany(RoomUnit::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
