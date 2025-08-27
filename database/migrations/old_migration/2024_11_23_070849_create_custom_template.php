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
        Schema::create('custom_template', function (Blueprint $table) {
            $table->id();
            $table->json('job_type');
            $table->json('engineers'); // Store multiple engineer IDs
            $table->integer('notification_template');
            $table->string('notification_title')->nullable();
            $table->string('notification_subtitle')->nullable();
            $table->text('notification_text')->nullable();
            $table->string('notification_interval')->nullable();
            $table->string('time')->nullable();
            $table->integer('month')->nullable();
            $table->string('weekday')->nullable();
            $table->integer('day')->nullable();
            $table->date('custom_date')->nullable(); // Change to date type
            $table->date('end_date')->nullable(); // Change to date type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_template');
    }
};
