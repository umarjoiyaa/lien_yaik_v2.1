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
        Schema::create('temperature_moistures', function (Blueprint $table) {
            $table->id();
            $table->string('machine_id')->nullable();
            $table->string('temperature_low')->nullable();
            $table->string('moisture_low')->nullable();
            $table->string('temperature_high')->nullable();
            $table->string('moisture_high')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temperature_moistures');
    }
};
