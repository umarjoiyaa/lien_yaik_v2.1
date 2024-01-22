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
        Schema::create('presses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pic')->nullable();
            $table->foreign('pic')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->foreign('machine_id')->references('id')->on('machines')->onDelete('cascade');
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
        Schema::dropIfExists('presses');
    }
};
