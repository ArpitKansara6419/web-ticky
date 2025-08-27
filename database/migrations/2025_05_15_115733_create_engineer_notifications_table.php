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
        Schema::create('engineer_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineer_id');
            $table->string('title');
            $table->string('body');
            $table->string('notification_type');
            $table->json('response_additional_data')->nullable();
            $table->dateTime('seen_at')->nullable();
            $table->boolean('is_seen')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_notifications');
    }
};
