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
        Schema::create('viomia_trade_executions', function (Blueprint $table) {
            $table->id();
            $table->string('account_id', 50)->index();
            $table->unsignedBigInteger('ticket')->unique();
            $table->string('symbol', 20);
            $table->string('decision', 20);
            $table->float('ml_confidence');
            $table->string('signal_combo', 100)->index();
            $table->string('regime_type', 50)->index();
            $table->decimal('entry_price', 12, 5);
            $table->float('profit_loss')->nullable();
            $table->string('result', 10)->nullable()->index();
            $table->string('session_name', 20);
            $table->timestamps();

            $table->unique(['account_id', 'ticket']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viomia_trade_executions');
    }
};
