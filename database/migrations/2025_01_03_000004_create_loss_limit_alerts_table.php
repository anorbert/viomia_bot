<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loss_limit_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->double('daily_loss');
            $table->double('daily_loss_limit');
            $table->enum('limit_type', ['USD', 'PERCENT']);
            $table->double('balance');
            $table->double('equity');
            $table->timestamp('alert_at');
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->index('alert_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loss_limit_alerts');
    }
};
