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
        Schema::create('ticket_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->text('notification_text')->nullable();
            $table->foreignId('engineer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('engineer_device_token')->nullable();
            $table->string('notification_type')->nullable();
            $table->boolean('is_repeat')->default(0);
            $table->enum('status', ['sent', 'pending'])->default('pending');
            $table->time('last_send_time')->nullable();
            $table->time('next_send_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_notifications');
    }
};
