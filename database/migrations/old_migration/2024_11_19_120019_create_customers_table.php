<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique()->nullable();
            $table->string('name');
            $table->enum('customer_type',['company', 'freelancer']);
            $table->string('company_reg_no')->nullable();
            $table->text('address')->nullable();
            $table->string('vat_no')->nullable();
            $table->string('email')->unique();
            $table->string('auth_person')->nullable();
            $table->string('auth_person_email')->unique()->nullable();
            $table->string('auth_person_contact')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->string('profile_image')->nullable();
            $table->string('doc_ref')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
