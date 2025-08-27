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
        Schema::create('engineer_language_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('language_name');
            $table->string('proficiency_level')->nullable(); // e.g., beginner, intermediate, advanced
            $table->boolean('read')->default(0); // 0 = No, 1 = Yes
            $table->boolean('write')->default(0); // 0 = No, 1 = Yes
            $table->boolean('speak')->default(0); // 0 = No, 1 = Yes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_language_skills');
    }
};
