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
        Schema::create('customer_payables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->unsignedBigInteger('ticket_work_id');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->time('total_work_time')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('halfday_rate', 10, 2)->nullable();
            $table->decimal('fullday_rate', 10, 2)->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->decimal('hourly_payable', 10, 2)->nullable();
            $table->decimal('overtime_payable', 10, 2)->nullable();
            $table->decimal('client_payable', 10, 2)->nullable();
            $table->time('overtime_hour')->nullable();
            $table->decimal('travel_cost', 10, 2)->nullable();
            $table->decimal('tool_cost', 10, 2)->nullable();
            $table->decimal('food_cost', 10, 2)->nullable();
            $table->decimal('other_pay', 10, 2)->nullable();
            $table->integer('is_overtime')->default(0);
            $table->integer('is_holiday')->default(0);
            $table->integer('is_weekend')->default(0);
            $table->integer('is_out_of_office_hours')->default(0);
            $table->decimal('ot_payable', 10, 2)->nullable();
            $table->decimal('ooh_payable', 10, 2)->nullable();
            $table->decimal('ww_payable', 10, 2)->nullable();
            $table->decimal('hw_payable', 10, 2)->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_payables');
    }
};
