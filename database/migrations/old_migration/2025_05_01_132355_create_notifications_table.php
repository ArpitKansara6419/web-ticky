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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');              // e.g. 'message', 'alert', etc.
            $table->string('title')->nullable(); // optional short title
            $table->text('message');             // main notification content
            $table->string('url')->nullable();   // optional link
            $table->json('meta')->nullable();    // optional extra data
            $table->boolean('is_read')->default(false); // read status
            $table->timestamps();                // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
