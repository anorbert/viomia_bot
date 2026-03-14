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
        Schema::create('viomia_decisions', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 20)->index();
            $table->string('decision', 10)->index();
            $table->decimal('confidence', 6, 4);
            $table->integer('score');
            $table->text('reasons');
            $table->decimal('entry', 18, 5);
            $table->decimal('stop_loss', 18, 5);
            $table->decimal('take_profit', 18, 5);
            $table->decimal('rr_ratio', 6, 2);
            $table->json('web_intel')->nullable();
            $table->string('web_sentiment', 10)->nullable();
            $table->integer('web_score_adj')->default(0);
            $table->string('account_id', 50);
            $table->timestamp('decided_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viomia_decisions');
    }
};
