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
        Schema::create('monthly_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engineer_id');
            $table->integer('year');
            $table->integer('month');
            $table->decimal('allocated_leaves', 4, 2)->default(1.66); // 20/12 = 1.66
            $table->decimal('used_leaves', 4, 2)->default(0);
            $table->decimal('carry_forward_leaves', 4, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_leave_balances');
    }
};
