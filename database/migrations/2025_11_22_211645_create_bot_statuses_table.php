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
        Schema::create('bot_statuses', function (Blueprint $table) {
            $table->id();
            $table->double('balance');
            $table->double('equity');
            $table->double('daily_pl');
            $table->integer('open_positions');
            $table->double('max_dd')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_statuses');
    }
};
