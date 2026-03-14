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
        Schema::table('users', function (Blueprint $table) {
            // Add login tracking columns
            $table->timestamp('last_login_at')->nullable()->after('updated_at')->comment('Last login timestamp');
            $table->timestamp('previous_login_at')->nullable()->after('last_login_at')->comment('Previous login timestamp');
            $table->integer('total_login_count')->default(0)->after('previous_login_at')->comment('Total number of logins');
            $table->integer('total_session_minutes')->default(0)->after('total_login_count')->comment('Total minutes spent in system');
            $table->timestamp('last_activity_at')->nullable()->after('total_session_minutes')->comment('Last activity timestamp during session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login_at',
                'previous_login_at',
                'total_login_count',
                'total_session_minutes',
                'last_activity_at',
            ]);
        });
    }
};
