<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ticket')->unique();
            $table->enum('direction', ['BUY', 'SELL']);
            $table->decimal('entry_price', 15, 5);
            $table->decimal('sl_price', 15, 5);
            $table->decimal('tp_price', 15, 5);
            $table->decimal('lot_size', 10, 2);
            $table->string('signal_source')->nullable();
            $table->timestamp('opened_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_events');
    }
};
