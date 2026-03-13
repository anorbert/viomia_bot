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
        Schema::create('weekly_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_subscription_id')->constrained('user_subscriptions')->onDelete('cascade');
            $table->date('week_start');
            $table->date('week_end');
            $table->decimal('weekly_profit', 12, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(30); // 30%
            $table->decimal('amount', 12, 2); // calculated amount
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['momo', 'binance'])->nullable(); // Only MOMO and Binance active
            $table->string('reference')->nullable(); // Transaction ID
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_payments');
    }
};
