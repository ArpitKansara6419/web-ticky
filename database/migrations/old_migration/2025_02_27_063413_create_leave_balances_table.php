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
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engineer_id');
            $table->integer('total_leaves')->default(0);
            $table->integer('used_leaves')->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('leave_credited_this_month', 10, 2)->default(0);
            $table->decimal('total_yearly_alloted', 10, 2)->default(0);
            $table->integer('total_paid_leave_used')->default(0);
            $table->integer('total_unpaid_leave_used')->default(0);
            $table->decimal('opening_balance_from_past_year', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
