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
        Schema::create('viomia_candle_logs', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 20)->index();
            $table->decimal('price', 18, 5);
            $table->decimal('rsi', 8, 4);
            $table->decimal('atr', 18, 5);
            $table->tinyInteger('trend');
            $table->decimal('resistance', 18, 5);
            $table->decimal('support', 18, 5);
            $table->tinyInteger('session');
            $table->string('account_id', 50);
            $table->longText('candles_json');
            $table->timestamp('received_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viomia_candle_logs');
    }
};
