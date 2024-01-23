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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('item_id')->nullable();
            $table->string('order_no')->nullable();
            $table->string('order_date')->nullable();
            $table->string('req_date')->nullable();
            $table->string('order_unit')->nullable();
            $table->string('cavities')->nullable();
            $table->string('unit_kg')->nullable();
            $table->string('per_mold')->nullable();
            $table->unsignedBigInteger('issued')->nullable();
            $table->foreign('issued')->references('id')->on('users')->onDelete('cascade');
            $table->string('approved')->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
};
