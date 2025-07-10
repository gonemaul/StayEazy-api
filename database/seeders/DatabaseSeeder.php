<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Room;
use App\Models\User;
use App\Models\Hotel;
use App\Models\RoomClass;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $jakarta = City::create(['name' => 'Jakarta']);
        $bandung = City::create(['name' => 'Bandung']);
        $yogyakarta = City::create(['name' => 'Yogyakarta']);

        $hotelJkt = Hotel::create(['name' => 'Hotel Melati', 'city_id' => $jakarta->id, 'address' => 'Jl. Merdeka 1', 'description' => 'Hotel sederhana di Jakarta']);
        $hotelBndg = Hotel::create(['name' => 'Villa Lembang', 'city_id' => $bandung->id, 'address' => 'Lembang, Bandung', 'description' => 'Villa pegunungan yang sejuk']);
        $hotelYogya = Hotel::create(['name' => 'Yogya Stay', 'city_id' => $yogyakarta->id, 'address' => 'Jl. Kaliurang', 'description' => 'Penginapan murah di Yogya']);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@stay.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Staff Jakarta',
            'email' => 'staff@stayjakarta.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'hotel_id' => $hotelJkt->id
        ]);

        User::create([
            'name' => 'Staff Bandung',
            'email' => 'staff@staybandung.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'hotel_id' => $hotelBndg->id
        ]);
        User::create([
            'name' => 'Staff Yogyakarta',
            'email' => 'staff@stayyogyakarta.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'hotel_id' => $hotelYogya->id
        ]);

        $this->call([
            RoomClassSeeder::class,
        ]);
    }
}