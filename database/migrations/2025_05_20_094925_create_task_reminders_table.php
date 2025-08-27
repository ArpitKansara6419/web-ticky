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
        Schema::create('task_reminders', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('ticket_id');
            $table->foreignId('customer_id');
            $table->foreignId('engineer_id');

            $table->string('task_type');
            $table->string('work_reminder_for');
            $table->dateTime('reminder_at');

            $table->string('user_response')->nullable();
            $table->text('reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_reminders');
    }
};
