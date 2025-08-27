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
        Schema::table('holidays', function (Blueprint $table) {
            $table->foreignId('holiday_sync_id')->nullable()->after('id');

            $table->text('note')->nullable()->change();

            $table->after('note', function (Blueprint $table) {
                $table->string('country_name')->nullable();
                $table->string('country_code')->nullable();
                $table->string('type')->nullable();
                $table->text('description')->nullable();
                $table->string('primary_type')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('holiday_sync_id');
            $table->dropColumn('country_name');
            $table->dropColumn('country_code');
            $table->dropColumn('type');
            $table->dropColumn('description');
            $table->dropColumn('primary_type');
        });
    }
};
