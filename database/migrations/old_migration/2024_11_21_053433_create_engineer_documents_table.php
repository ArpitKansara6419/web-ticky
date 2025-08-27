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
        Schema::create('engineer_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key reference to users table
            $table->string('document_type');
            $table->string('document_label')->nullable();
            $table->string('media_file')->nullable(); // Storing file
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('extra_info')->nullable(); // Storing file metadata
            $table->json('extra_data')->nullable(); // Storing file metadata as JSON
            $table->tinyInteger('status')->default(1); // Status default is active (1)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_documents');
    }
};
