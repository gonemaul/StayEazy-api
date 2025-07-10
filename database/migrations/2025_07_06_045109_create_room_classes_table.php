<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->integer('capacity')->default(2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_classes');
    }
};