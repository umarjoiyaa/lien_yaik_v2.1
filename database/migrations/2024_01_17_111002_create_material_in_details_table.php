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
        Schema::create('material_in_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mi_id')->nullable();
            $table->foreign('mi_id')->references('id')->on('material_ins')->onDelete('cascade');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('materials')->onDelete('cascade');
            $table->string('qty')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_in_details');
    }
};
