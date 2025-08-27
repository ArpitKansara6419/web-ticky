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
        Schema::create('engineer_payout', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engineer_id'); // Foreign key to engineer table
            $table->date('from_date')->nullable(); // Start date
            $table->date('to_date')->nullable(); // End date
            $table->json('ticket_work_ids'); // JSON field for work IDs
            $table->decimal('total_payable', 10, 2)->default(0.00); // Total amount to pay
            $table->decimal('extra_incentive', 10, 2)->default(0.00); // Incentive amount
            $table->decimal('gross_pay', 10, 2)->default(0.00); // Incentive amount
            $table->string('currency'); // Currency code
            $table->text('note')->nullable(); // Notes about the payout
            $table->date('payment_date')->nullable(); // Payment date
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('paid'); // Payment status
            $table->enum('payment_type', ['cash', 'bank_transfer'])->nullable(); // Payment method
            $table->enum('payout_type', ['monthly', 'dialy'])->default('dialy'); // Payment method
            $table->string('payout_month')->nullable();
            $table->decimal('zus', 10, 2)->default(0.00); // Incentive amount
            $table->decimal('pit', 10, 2)->default(0.00); // Incentive amount
            $table->timestamps(); // Created at and Updated at timestamps

            // Indexes                                                  
            $table->index('engineer_id');
            $table->index('payment_status');
            $table->index('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_payout');
    }
};
