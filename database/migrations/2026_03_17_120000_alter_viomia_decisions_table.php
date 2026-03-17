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
        Schema::table('viomia_decisions', function (Blueprint $table) {
            // Add missing columns that AI is trying to insert
            $table->decimal('rsi', 8, 4)->nullable()->after('rr_ratio');
            $table->decimal('atr', 18, 5)->nullable()->after('rsi');
            $table->tinyInteger('trend')->nullable()->after('atr');
            $table->tinyInteger('session')->nullable()->after('trend');
            $table->integer('dxy_trend')->default(0)->after('session');
            $table->integer('risk_off')->default(0)->after('dxy_trend');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viomia_decisions', function (Blueprint $table) {
            $table->dropColumn(['rsi', 'atr', 'trend', 'session', 'dxy_trend', 'risk_off']);
        });
    }
};
