<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ea_status_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->enum('status', ['RUNNING', 'PAUSED', 'ERROR_STOP', 'DAILY_LOSS_HIT']);
            $table->string('reason');
            $table->integer('consecutive_losses');
            $table->double('balance');
            $table->double('equity');
            $table->integer('positions_open');
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->index('status');
            $table->index('changed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ea_status_changes');
    }
};
