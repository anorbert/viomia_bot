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
        Schema::create('viomia_trade_outcomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket')->unique()->index();
            $table->string('account_id', 50)->default('0')->index();
            $table->string('symbol', 20);
            $table->decimal('profit', 12, 4);
            $table->string('result', 10)->index(); // WIN/LOSS
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viomia_trade_outcomes');
    }
};
