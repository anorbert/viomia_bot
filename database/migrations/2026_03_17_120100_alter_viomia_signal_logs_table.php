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
        Schema::table('viomia_signal_logs', function (Blueprint $table) {
            // Add missing columns that AI is trying to insert
            $table->decimal('stop_loss', 18, 5)->nullable()->after('entry');
            $table->decimal('take_profit', 18, 5)->nullable()->after('stop_loss');
            $table->decimal('confidence', 6, 4)->nullable()->after('take_profit');
            $table->integer('score')->nullable()->after('confidence');
            $table->string('account_id', 50)->nullable()->after('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viomia_signal_logs', function (Blueprint $table) {
            $table->dropColumn(['stop_loss', 'take_profit', 'confidence', 'score', 'account_id']);
        });
    }
};
