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
        Schema::create('viomia_signal_patterns', function (Blueprint $table) {
            $table->id();
            $table->string('pattern_name', 100)->index();
            $table->boolean('with_bos')->default(false);
            $table->boolean('with_equal_levels')->default(false);
            $table->string('web_sentiment', 20)->nullable();
            $table->string('market_regime', 50)->nullable()->index();
            $table->string('decision', 20)->nullable();
            $table->string('result', 10)->nullable()->index();
            $table->float('profit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viomia_signal_patterns');
    }
};
