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
        Schema::create('temperature_moisture_apis', function (Blueprint $table) {
            $table->id();
            $table->string('machine_id')->nullable();
            $table->string('batch_id')->nullable();
            $table->string('temperature')->nullable();
            $table->string('moisture')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temperature_moisture_apis');
    }
};
