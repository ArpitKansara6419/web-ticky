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
        Schema::create('engineer_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engineer_id');
            $table->date('paid_from_date');
            $table->date('paid_to_date');
            $table->date('unpaid_from_date');
            $table->date('unpaid_to_date');
            $table->integer('paid_leave_days');
            $table->integer('unpaid_leave_days');
            $table->text('unpaid_reasone')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->default(0);
            $table->enum('leave_approve_status', ['pending', 'approved', 'reject'])->default('pending');
            $table->text('signed_paid_document')->nullable();
            $table->text('unsigned_paid_document')->nullable();
            $table->text('signed_unpaid_document')->nullable();
            $table->text('unsigned_unpaid_document')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_leaves');
    }
};
