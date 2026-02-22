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
    Schema::table('subscription_plans', function (Blueprint $table) {
        // This adds the 'deleted_at' column
        $table->softDeletes()->after('updated_at'); 
    });
}

public function down(): void
{
    Schema::table('subscription_plans', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
};
