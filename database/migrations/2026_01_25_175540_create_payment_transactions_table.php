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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
            $table->string('reference')->unique(); // your internal reference/order id
            $table->enum('provider', ['momo','binance','bank'])->index();

            $table->string('currency', 10)->default('RWF');
            $table->decimal('amount', 12, 2)->default(0);

            $table->enum('status', ['pending','success','failed','cancelled','expired'])->default('pending')->index();

            // provider identifiers
            $table->string('provider_txn_id')->nullable()->index();
            $table->string('checkout_url')->nullable();

            $table->json('payload')->nullable();   // raw callback / metadata
            $table->timestamp('paid_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
