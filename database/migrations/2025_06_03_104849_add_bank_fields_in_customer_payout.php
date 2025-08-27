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
        Schema::table('customer_payout', function (Blueprint $table) {
            $table->foreignId('bank_id')->nullable()->after('payment_type');
            $table->json('bank_details')->nullable()->after('bank_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_payout', function (Blueprint $table) {
            $table->dropColumn([
                'bank_id',
                'bank_details'
            ]);
        });
    }
};
