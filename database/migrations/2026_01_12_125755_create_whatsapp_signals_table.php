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
        Schema::create('whatsapp_signals', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('whatsapp');
            $table->string('group_id');
            $table->string('sender');
            $table->string('symbol');
            $table->enum('type', ['BUY', 'SELL']);
            $table->decimal('entry', 10, 2);
            $table->decimal('stop_loss', 10, 2);
            $table->json('take_profit');
            $table->text('raw_text')->nullable();
            $table->string('status')->default('pending');            
            $table->timestamp('received_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_signals');
    }
};
