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
        Schema::create('engineer_extra_pays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engineer_id');
            $table->float('overtime')->nullable();; // Pay with default value of 1
            $table->float('out_of_office_hour')->nullable();; // Pay with default value of 1
            $table->float('weekend')->nullable();; // Pay with default value of 1
            $table->float('public_holiday')->nullable();; // Pay with default value of 1
            $table->tinyInteger('status')->default(1); // Status (0 or 1)   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_extra_pays');
    }
};
