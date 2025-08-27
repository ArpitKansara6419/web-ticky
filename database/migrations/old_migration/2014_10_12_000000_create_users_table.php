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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_code')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('country_code')->nullable();
            $table->string('contact')->nullable();
            $table->text('address')->nullable(); // Added address field
            $table->date('dob')->nullable(); // Added date of birth field
            $table->tinyInteger('status')->default(1);
            $table->string('profile_image')->nullable();
            $table->string('otp')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('email_verification')->default(true);
            $table->boolean('admin_verification')->default(true);
            $table->boolean('phone_verification')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('previous_login_at')->nullable();
        

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
