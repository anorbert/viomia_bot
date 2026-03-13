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
        Schema::table('account_snapshots', function (Blueprint $table) {
            //
            $table->decimal('initial_balance', 15, 2)->after('account_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_snapshots', function (Blueprint $table) {
            //
            $table->dropColumn('initial_balance');
        });
    }
};
