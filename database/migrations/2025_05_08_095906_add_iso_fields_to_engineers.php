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
        Schema::table('engineers', function (Blueprint $table) {
            $table->string('contact_iso', 5)->after('contact')->nullable();
            $table->string('alternate_contact_iso', 5)->after('alternative_contact')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('engineers', function (Blueprint $table) {
            $table->dropColumn([
                'contact_iso',
                'alternate_contact_iso'
            ]);
        });
    }
};
