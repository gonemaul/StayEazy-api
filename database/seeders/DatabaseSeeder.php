<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Hotel;
use App\Models\RoomClass;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $jakarta = City::create(['name' => 'Jakarta']);
        $bandung = City::create(['name' => 'Bandung']);
        $yogyakarta = City::create(['name' => 'Yogyakarta']);

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
            'hotel_id' => $jakarta->id
        ]);

        User::create([
            'name' => 'Staff Bandung',
            'email' => 'staff@staybandung.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'hotel_id' => $bandung->id
        ]);
        User::create([
            'name' => 'Staff Yogyakarta',
            'email' => 'staff@stayyogyakarta.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'hotel_id' => $yogyakarta->id
        ]);

        Hotel::create(['name' => 'Hotel Melati', 'city_id' => $jakarta->id, 'address' => 'Jl. Merdeka 1', 'description' => 'Hotel sederhana di Jakarta']);
        Hotel::create(['name' => 'Villa Lembang', 'city_id' => $bandung->id, 'address' => 'Lembang, Bandung', 'description' => 'Villa pegunungan yang sejuk']);
        Hotel::create(['name' => 'Yogya Stay', 'city_id' => $yogyakarta->id, 'address' => 'Jl. Kaliurang', 'description' => 'Penginapan murah di Yogya']);

        $roomClasses = ['Standard', 'Deluxe', 'Superior', 'Suite', 'Executive'];
        foreach ($roomClasses as $class) {
            RoomClass::create(['name' => $class]);
        }
    }
}