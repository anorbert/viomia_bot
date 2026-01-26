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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->restrictOnDelete();
            $table->enum('status', ['active','pending','expired','cancelled'])->default('pending');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('auto_renew')->default(false);
            // Optional tracking fields (useful for payments)
            $table->string('reference')->nullable();      // invoice/payment ref
            $table->decimal('amount', 12, 2)->default(0); // snapshot amount paid
            $table->string('currency', 3)->nullable(); // snapshot currency
            $table->string('payment_method')->nullable(); // e.g., stripe, paypal
            $table->text('notes')->nullable(); // admin notes or payment details
            $table->softDeletes();
            $table->timestamps();
            $table->index(['user_id','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
