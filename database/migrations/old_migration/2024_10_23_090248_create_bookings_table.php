<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('booker_id');
            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('sport_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('slot_id');
            $table->string('day');
            $table->date('date');
            $table->time('slot_time_start');
            $table->time('slot_time_end');
            $table->integer('slot_duration');
            $table->integer('slot_charge');
            $table->decimal('total_charge');
            $table->tinyInteger('payment_status')->default(0);
            $table->string('payment_mode')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
