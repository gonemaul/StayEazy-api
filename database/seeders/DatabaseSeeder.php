<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Hotel;
use App\Models\RoomClass;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $jakarta = City::create(['name' => 'Jakarta']);
        $bandung = City::create(['name' => 'Bandung']);
        $yogyakarta = City::create(['name' => 'Yogyakarta']);

        Hotel::create(['name' => 'Hotel Melati', 'city_id' => $jakarta->id, 'alamat' => 'Jl. Merdeka 1', 'deskripsi' => 'Hotel sederhana di Jakarta']);
        Hotel::create(['name' => 'Villa Lembang', 'city_id' => $bandung->id, 'alamat' => 'Lembang, Bandung', 'deskripsi' => 'Villa pegunungan yang sejuk']);
        Hotel::create(['name' => 'Yogya Stay', 'city_id' => $yogyakarta->id, 'alamat' => 'Jl. Kaliurang', 'deskripsi' => 'Penginapan murah di Yogya']);

        $roomClasses = ['Standard', 'Deluxe', 'Superior', 'Suite', 'Executive'];
        foreach ($roomClasses as $class) {
            RoomClass::create(['name' => $class]);
        }
    }
}