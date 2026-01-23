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
        Schema::table('trade_logs', function (Blueprint $table) {
            //
            $table->double('closed_lots')->default(0)->after('lots');
            $table->string('status')->default('open')->after('profit'); // open|partial_closed|closed
            $table->string('close_reason')->nullable()->after('status'); // optional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trade_logs', function (Blueprint $table) {
            //
            $table->dropColumn(['closed_lots', 'status', 'close_reason']);
        });
    }
};
