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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('platform'); // MT4, MT5, cTrader
            $table->string('server');
            $table->string('login');
            $table->string('password'); // encrypted
            $table->string('account_type')->nullable(); // real/dem0
            $table->boolean('active')->default(true);
            
            $table->boolean('connected')->default(false); // auto-update after health check
            $table->json('meta')->nullable(); // leverage, currency, etc.
            
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
