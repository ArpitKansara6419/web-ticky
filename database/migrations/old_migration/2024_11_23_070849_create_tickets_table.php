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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique()->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('lead_id');
            $table->string('client_name');
            $table->string('apartment')->nullable();
            $table->string('add_line_1')->nullable();
            $table->string('add_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('zipcode')->nullable();
            $table->date('task_start_date')->nullable();
            $table->date('task_end_date')->nullable();
            $table->time('task_time');
            $table->string('task_name');
            $table->string('rate_type')->nullable();
            $table->text('scope_of_work')->nullable();
            $table->string('poc_details')->nullable();
            $table->string('re_details')->nullable();
            $table->string('call_invites')->nullable();
            $table->string('ref_sign_sheet')->nullable();
            $table->string('documents')->nullable();
            $table->unsignedBigInteger('engineer_id');
            $table->integer('standard_rate')->nullable();
            $table->double('travel_cost')->nullable();
            $table->double('tool_cost')->nullable();          
            $table->string('food_expenses')->nullable();     
            $table->string('misc_expenses')->nullable();    
            $table->string('currency_type')->nullable();   
            $table->string('engineer_status')->default('offered');   
            $table->string('status')->nullable('open');         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
