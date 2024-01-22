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
        Schema::create('production_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id')->nullable();
            $table->foreign('po_id')->references('id')->on('production_orders')->onDelete('cascade');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('materials')->onDelete('cascade');
            $table->string('available')->nullable();
            $table->string('required')->nullable();
            $table->string('need')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_order_details');
    }
};
