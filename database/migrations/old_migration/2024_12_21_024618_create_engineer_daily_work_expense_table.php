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
        Schema::create('engineer_daily_work_expense', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_work_id'); // Foreign Key for engineers
            $table->unsignedBigInteger('engineer_id'); // Foreign Key for engineers
            $table->unsignedBigInteger('ticket_id'); // Foreign Key for tickets
            $table->string('document')->nullable(); // File path or name
            $table->string('name')->nullable(); // File path or name
            $table->double('expense', 8, 2); // Expense amount
            $table->tinyInteger('status')->default(0); // Status (e.g., 0 = pending)
            $table->timestamps(); // Created_at and updated_at
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_daily_work_expense');
    }
};
