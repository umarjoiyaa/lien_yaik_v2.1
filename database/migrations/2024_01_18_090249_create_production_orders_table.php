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
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->foreign('operator_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('machine_id')->nullable();
            $table->string('order_date')->nullable();
            $table->string('target_produce')->nullable();
            $table->string('due_date')->nullable();
            $table->string('issued_date')->nullable();
            $table->string('no_cavity')->nullable();
            $table->string('reject_qty')->nullable();
            $table->string('weight_unit')->nullable();
            $table->string('order_unit')->nullable();
            $table->string('used_qty')->nullable();
            $table->string('available_qty')->nullable();
            $table->string('target_need')->nullable();
            $table->string('weight_mold')->nullable();
            $table->string('mold_shot')->nullable();
            $table->string('raw_material')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
