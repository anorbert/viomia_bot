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
        Schema::table('viomia_error_logs', function (Blueprint $table) {
            // Add account_id for multi-account filtering
            $table->string('account_id', 50)->default('0')->after('error_type')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viomia_error_logs', function (Blueprint $table) {
            $table->dropColumn(['account_id']);
        });
    }
};
