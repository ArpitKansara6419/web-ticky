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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('title'); // Holiday title
            $table->date('date'); // Holiday date
            $table->string('note')->nullable(); // Optional holiday note
            $table->boolean('status')->default(1); // Status: 1 for active, 0 for inactive
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
