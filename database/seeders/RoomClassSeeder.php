<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\RoomUnit;
use App\Models\RoomClass;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua hotel ID
        $hotelIds = Hotel::pluck('id');

        if ($hotelIds->isEmpty()) {
            $this->command->warn('Tidak ada hotel tersedia. Harap seed hotel terlebih dahulu.');
            return;
        }

        $roomTypes = ['Deluxe', 'Superior', 'Suite'];

        // Looping untuk beberapa class
        foreach ($roomTypes as $type) {
            $randomHotelId = $hotelIds->random();

            $class = RoomClass::create([
                'hotel_id' => $randomHotelId,
                'name' => $type,
                'price' => rand(400000, 900000),
                'capacity' => rand(2, 4),
                'description' => "Kamar tipe {$type} dengan fasilitas bervariasi",
            ]);

            foreach (range(1, rand(2, 5)) as $i) {
                RoomUnit::create([
                    'room_class_id' => $class->id,
                    'room_number' => strtoupper(substr($type, 0, 1)) . rand(100, 999),
                ]);
            }
        }
    }
}