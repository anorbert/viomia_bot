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
        Schema::create('ea_whatsapp_excutions', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('whatsapp_signal_id')->constrained()->cascadeOnDelete();
            $table->string('account_id'); // MT5 login or unique EA account ID
            $table->enum('status', ['received', 'executed', 'failed'])->default('received');
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ea_whatsapp_excutions');
    }
};
