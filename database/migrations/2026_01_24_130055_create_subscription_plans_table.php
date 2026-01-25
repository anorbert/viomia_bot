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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            $table->string('currency', 10)->default('RWF');
            $table->decimal('price', 12, 2)->default(0);

            $table->enum('billing_interval', ['monthly','yearly','weekly','daily'])->default('monthly');
            $table->unsignedInteger('duration_days')->nullable(); // optional override

            $table->text('description')->nullable();
            $table->json('features')->nullable();

            $table->integer('profit_share')->default(0); // percentage

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
