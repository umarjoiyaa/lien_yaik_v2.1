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
        Schema::create('warehouse_ins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->string('date')->nullable();
            $table->longText('remarks')->nullable();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->foreign('operator_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('total_pcs')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_ins');
    }
};
