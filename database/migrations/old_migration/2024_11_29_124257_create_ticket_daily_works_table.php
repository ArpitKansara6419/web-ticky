<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_daily_work', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->date('work_start_date')->nullable();
            $table->date('work_end_date')->nullable();
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->time('total_work_time')->nullable();
            $table->time('overtime_hour')->nullable();
            $table->float('hourly_rate')->nullable();
            $table->float('halfday_rate')->nullable();
            $table->float('fullday_rate')->nullable();
            $table->float('monthly_rate')->nullable();
            $table->float('hourly_payable')->nullable();
            $table->float('overtime_payable')->nullable();
            $table->float('out_of_office_payable')->nullable(); // new field
            $table->float('weekend_payable')->nullable(); // new field
            $table->float('holiday_payable')->nullable(); // new field
            $table->integer('is_overtime')->default(0);
            $table->integer('is_overtime_approved')->default(0);
            $table->integer('is_holiday')->default(0);
            $table->integer('is_weekend')->default(0);
            $table->integer('is_out_of_office_hours')->default(0);
            $table->float('travel_cost')->nullable();
            $table->float('tool_cost')->nullable();
            $table->float('food_cost')->nullable();
            $table->float('other_pay')->nullable();
            $table->float('daily_gross_pay')->nullable();
            $table->text('note')->nullable();
            $table->text('document_file')->nullable();
            $table->text('address')->nullable();
            $table->point('location')->nullable();
            $table->string('status')->default('inprogress');
            $table->string('engineer_payout_status')->default('pending');
            $table->string('client_payment_status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_daily_work');
    }
    
};
