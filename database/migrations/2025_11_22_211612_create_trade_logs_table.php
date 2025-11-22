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
        Schema::create('trade_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ticket')->nullable();
            $table->string('symbol');
            $table->enum('type', ['buy', 'sell']);
            $table->double('lots');
            $table->double('sl');
            $table->double('tp');
            $table->double('open_price');
            $table->double('close_price')->nullable();
            $table->double('profit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_logs');
    }
};
