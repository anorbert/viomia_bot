<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create table for storing outcomes that failed to insert
     * Used for retry mechanism - solves P0-2 and P0-3
     * 
     * When outcome insert fails:
     * 1. Store in this table
     * 2. Background job retries every 5 seconds
     * 3. Retry with exponential backoff (max 10 retries)
     * 4. After successful insert, remove from queue
     */
    public function up(): void
    {
        Schema::create('outcome_failures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket')->unique()->index();
            $table->string('account_id', 50)->index();
            $table->string('symbol', 20);
            $table->decimal('profit', 12, 4);
            $table->string('result', 10);  // WIN or LOSS
            $table->json('outcome_data');  // Full outcome payload
            $table->integer('retry_count')->default(0);
            $table->integer('max_retries')->default(10);
            $table->text('last_error')->nullable();
            $table->timestamp('first_attempt_at')->useCurrent();
            $table->timestamp('last_retry_at')->nullable();
            $table->timestamp('next_retry_at')->nullable();
            $table->timestamps();
            
            // Indices for finding retry candidates
            $table->index(['retry_count', 'next_retry_at']);
            $table->index(['account_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcome_failures');
    }
};
