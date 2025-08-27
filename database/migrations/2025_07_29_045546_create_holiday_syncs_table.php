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
        Schema::create('holiday_syncs', function (Blueprint $table) {
            $table->id();
            $table->string('country_name');
            $table->string('iso_3166');
            $table->string('total_holidays');
            $table->string('supported_languages');
            $table->string('uuid');
            $table->string('flag_unicode');
            $table->string('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holiday_syncs');
    }
};
