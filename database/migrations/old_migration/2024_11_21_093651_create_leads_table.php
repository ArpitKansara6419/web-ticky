f<?php

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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('lead_code')->unique()->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->enum('lead_type',['full_time','short_term','dispatch_term']);
            $table->string('end_client_name')->nullable();
            $table->string('client_ticket_no')->nullable();

            $table->date('task_start_date')->nullable();
            $table->date('task_end_date')->nullable();
            $table->time('task_time')->nullable();
            $table->string('apartment')->nullable();
            $table->string('add_line_1')->nullable();
            $table->string('add_line_2')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();

            $table->text('scope_of_work')->nullable();
            $table->enum('rate_type',['standard','manually']);
            $table->string('currency_type')->nullable();
            $table->double('hourly_rate')->nullable();
            $table->double('half_day_rate')->nullable();
            $table->double('full_day_rate')->nullable();
            $table->double('monthly_rate')->nullable();
            $table->enum('lead_status',['bid', 'confirm', 'reschedule', 'cancelled'])->default('bid');
            $table->date('reschedule_date')->nullable();
            $table->double('travel_cost')->nullable();
            $table->double('tool_cost')->nullable();     
            $table->integer('is_ticket_created')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
