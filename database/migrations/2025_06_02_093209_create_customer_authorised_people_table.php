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
        Schema::create('customer_authorised_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('person_name')->nullable();
            $table->string('person_email')->nullable();
            $table->string('person_contact_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_authorised_people');
    }
};
