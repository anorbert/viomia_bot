<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filter_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('filter_type'); // ASIA, LONDON_DISABLED, NEWS, CORRELATION
            $table->string('block_reason');
            $table->timestamp('blocked_at');
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->index('filter_type');
            $table->index('blocked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filter_blocks');
    }
};
