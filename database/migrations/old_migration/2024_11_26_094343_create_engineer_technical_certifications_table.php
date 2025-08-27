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

        Schema::create('engineer_technical_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->date('issue_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->text('document')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('engineers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_technical_certifications');
    }
};
