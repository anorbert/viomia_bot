<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make rr_ratio nullable since NO_TRADE decisions don't have one
     * Fixes: Column 'rr_ratio' cannot be null errors
     */
    public function up(): void
    {
        Schema::table('viomia_decisions', function (Blueprint $table) {
            $table->decimal('rr_ratio', 6, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('viomia_decisions', function (Blueprint $table) {
            $table->decimal('rr_ratio', 6, 2)->nullable(false)->change();
        });
    }
};
