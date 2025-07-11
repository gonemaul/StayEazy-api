<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city_id', 'address', 'description'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function roomClasses()
    {
        return $this->hasMany(RoomClass::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}