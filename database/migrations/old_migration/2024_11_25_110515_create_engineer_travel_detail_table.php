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
        Schema::create('engineer_travel_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->boolean('driving_license')->default(0); // 0 = No, 1 = Yes
            $table->boolean('own_vehicle')->default(0); // 0 = No, 1 = Yes
            $table->integer('working_radius')->nullable();
            $table->json('type_of_vehicle')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_travel_detail');
    }
};
