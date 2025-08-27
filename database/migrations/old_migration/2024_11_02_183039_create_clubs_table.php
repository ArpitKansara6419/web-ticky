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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('city', 50);
            $table->string('pincode');
            $table->string('location')->nullable();
            $table->string('contact');
            $table->string('email')->unique();
            $table->string('description')->nullable();
            $table->string('address');
            $table->tinyInteger('status');
            $table->string('website')->nullable();          
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->string('facebook')->nullable();          
            $table->string('instagram')->nullable();          
            $table->string('twitter')->nullable();          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
