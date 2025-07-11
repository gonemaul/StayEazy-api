<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_unit_id')->constrained()->onDelete('cascade');

            $table->dateTime('checkin_date');
            $table->dateTime('checkout_date');

            $table->integer('guest_count')->default(1);;

            $table->decimal('amount_price', 12, 2);
            $table->decimal('late_fee', 12, 2)->nullable();
            $table->string('status');
            $table->string('code_reservation')->unique();
            $table->string('payment_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
