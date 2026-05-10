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
        Schema::create('soil_samples', function (Blueprint $table) {
            $table->id();
            $table->string('sample_id')->unique();
            $table->string('location')->nullable();
            $table->date('sample_date')->nullable();
            $table->string('soil_type')->nullable();
            $table->string('crop_type')->nullable();
            $table->decimal('ph_value', 4, 2)->nullable();
            $table->integer('nitrogen')->nullable();
            $table->integer('phosphorus')->nullable();
            $table->integer('potassium')->nullable();
            $table->integer('calcium')->nullable();
            $table->integer('magnesium')->nullable();
            $table->integer('sulfur')->nullable();
            $table->integer('moisture_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soil_samples');
    }
};
