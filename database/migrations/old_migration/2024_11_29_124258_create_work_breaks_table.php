<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_breaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_work_id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('engineer_id');
            $table->date('work_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->time('total_break_time')->nullable();
            $table->point('location')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_breaks');
    }
};
