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
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('active');
            $table->timestamp('verified_at')->nullable()->after('is_verified');
            $table->text('verification_notes')->nullable()->after('verified_at');
            $table->text('rejection_reason')->nullable()->after('verification_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'verified_at', 'verification_notes', 'rejection_reason']);
        });
    }
};
