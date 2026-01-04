<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('position_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('ticket')->unique();
            $table->double('entry_price', 15, 5);
            $table->double('current_price', 15, 5);
            $table->double('unrealized_pl');
            $table->double('unrealized_pl_percent');
            $table->double('lot_size');
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->index('ticket');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_updates');
    }
};
