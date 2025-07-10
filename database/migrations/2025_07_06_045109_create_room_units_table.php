<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_class_id')->constrained()->onDelete('cascade');
            $table->string('room_number');
            $table->enum('status', ['available', 'maintenance'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_units');
    }
};