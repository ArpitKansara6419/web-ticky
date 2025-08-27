<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engineers', function (Blueprint $table) {
            $table->id();
            $table->string('engineer_code')->unique()->nullable();
            $table->string('first_name')->nullable(); // First name of the engineer
            $table->string('last_name')->nullable(); // Last name of the engineer
            $table->string('username')->unique()->nullable(); // Unique username
            $table->string('email')->unique(); // Engineer's email (unique)
            $table->string('country_code')->nullable(); // Engineer's email (unique)
            $table->string('alternate_country_code')->nullable(); // Engineer's email (unique)
            $table->string('password'); // Phone number
            $table->string('contact')->nullable(); // Phone number
            $table->string('alternate_country_code')->nullable();   // mobile number
            $table->string('alternative_contact')->nullable();  // mobile number
            $table->string('job_type')->nullable(); //  Job Type
            $table->string('job_title')->nullable(); //  Job Title
            $table->date('job_start_date')->nullable(); // Jon Start date
            $table->text('about')->nullable(); // About the engineer
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Gender options
            $table->text('profile_image')->nullable(); // Profile photo URL
            $table->string('addr_apartment')->nullable(); // Address Apartment
            $table->string('addr_street')->nullable(); // Address Street
            $table->string('addr_address_line_1')->nullable(); // Address Line 1
            $table->string('addr_address_line_2')->nullable(); // Address Line 2
            $table->string('addr_zipcode')->nullable(); // Address Street
            $table->string('addr_city')->nullable(); // Address City
            $table->string('addr_country')->nullable(); // Address Country
            $table->date('birthdate')->nullable(); // Birthdate
            $table->string('nationality')->nullable(); // Nationality
            $table->string('right_to_work')->nullable()->comment('student_visa, work_permit, dependent_visa, others'); // Nationality
            $table->string('referral')->nullable(); // Referral source
            $table->tinyInteger('email_verification')->default(0); // Email verificaiton status
            $table->tinyInteger('admin_verification')->default(1); // Admin verification status
            $table->tinyInteger('phone_verification')->default(0); // phone verification status
            $table->string('otp')->nullable(); // verification OTP
            $table->tinyInteger('status')->default(1); // phone verification status
            $table->tinyInteger('gdpr_consent')->default(1); // phone verification status
            $table->string('api_token', 80)->unique()->nullable();
            $table->string('device_token')->nullable(); // Device Token
            $table->tinyInteger('is_deleted')->default(0);
            // $table->integer('monthly_payout')->default(0);
            $table->json('monthly_payout')->nullable()->change();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('engineers');
    }
};
