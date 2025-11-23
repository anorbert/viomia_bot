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
        Schema::create('news_events', function (Blueprint $table) {
            $table->id();
            $table->string('currency');
            $table->string('event_name');
            $table->dateTime('event_time');
            $table->enum('impact', ['low', 'medium', 'high']);
            $table->text('raw')->nullable(); // full JSON from API
            $table->string('previous')->nullable();
            $table->string('forecast')->nullable();
            $table->string('actual')->nullable();
            $table->boolean('notified')->default(false);
            $table->string('status')->default('upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_events');
    }
};
