<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('error_type'); // INVALID_ORDER, SPREAD_TOO_HIGH, SL_INVALID, etc.
            $table->text('error_message');
            $table->double('price_at_error', 15, 5)->nullable();
            $table->double('balance');
            $table->double('equity');
            $table->timestamp('error_at');
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->index('error_type');
            $table->index('error_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
