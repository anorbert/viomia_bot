<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_signals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->double('trend_score');
            $table->enum('choch_signal', ['BULLISH_REVERSAL', 'BEARISH_REVERSAL', 'NO_REVERSAL']);
            $table->double('rsi_value');
            $table->double('atr_value', 15, 5);
            $table->double('ema_20', 15, 5);
            $table->double('ema_50', 15, 5);
            $table->string('signal_description');
            $table->timestamp('captured_at');
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->index('captured_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_signals');
    }
};
