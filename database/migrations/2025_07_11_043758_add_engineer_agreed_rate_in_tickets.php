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
        Schema::table('tickets', function (Blueprint $table) {
            $table->double('engineer_agreed_rate')->nullable()->after('misc_expenses');
            $table->boolean('is_engineer_agreed_rate')->default(0)->after('engineer_agreed_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'engineer_agreed_rate',
                'is_engineer_agreed_rate'
            ]);
        });
    }
};
