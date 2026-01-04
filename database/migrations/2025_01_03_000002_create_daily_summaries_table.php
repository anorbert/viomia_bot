<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->date('summary_date');
            $table->double('daily_pl');
            $table->integer('trades_count');
            $table->integer('winning_trades');
            $table->integer('losing_trades');
            $table->double('win_rate_percent');
            $table->double('balance');
            $table->double('equity');
            $table->timestamp('captured_at');
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->unique(['account_id', 'summary_date']);
            $table->index('summary_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_summaries');
    }
};
