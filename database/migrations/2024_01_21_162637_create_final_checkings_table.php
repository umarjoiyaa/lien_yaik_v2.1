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
        Schema::create('final_checkings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->string('remarks')->nullable();
            $table->string('date')->nullable();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->foreign('operator_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('total_good_weight')->nullable();
            $table->string('total_good_pcs')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_checkings');
    }
};
