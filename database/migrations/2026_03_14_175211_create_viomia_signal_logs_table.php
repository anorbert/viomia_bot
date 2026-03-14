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
        Schema::create('viomia_signal_logs', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 20)->index();
            $table->string('decision', 10);
            $table->decimal('entry', 18, 5);
            $table->string('push_status', 20);
            $table->text('laravel_resp')->nullable();
            $table->timestamp('pushed_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viomia_signal_logs');
    }
};
