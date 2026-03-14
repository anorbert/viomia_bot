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
        Schema::create('viomia_model_versions', function (Blueprint $table) {
            $table->id();
            $table->integer('version')->index();
            $table->integer('samples')->default(0);
            $table->decimal('accuracy', 6, 4)->nullable();
            $table->decimal('win_rate', 6, 4)->nullable();
            $table->decimal('old_accuracy', 6, 4)->nullable();
            $table->boolean('improved')->default(false);
            $table->timestamp('trained_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viomia_model_versions');
    }
};
