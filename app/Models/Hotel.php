<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city_id', 'alamat', 'deskripsi'];

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function rooms() {
        return $this->hasMany(Room::class);
    }
}