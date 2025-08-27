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
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('clubs');
        Schema::dropIfExists('event_blockers');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('slots');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
