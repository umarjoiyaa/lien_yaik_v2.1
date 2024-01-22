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
        Schema::create('machine_api_sums', function (Blueprint $table) {
            $table->id();
            $table->string('machine_id')->nullable();
            $table->string('batch_id')->nullable();
            $table->string('press_id')->nullable();
            $table->string('sum_cavity')->nullable();
            $table->string('date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_api_sums');
    }
};
