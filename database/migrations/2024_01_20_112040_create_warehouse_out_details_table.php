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
        Schema::create('warehouse_out_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pellete_id')->nullable();
            $table->foreign('pellete_id')->references('id')->on('pelletes')->onDelete('cascade');
            $table->unsignedBigInteger('wo_id')->nullable();
            $table->foreign('wo_id')->references('id')->on('warehouse_outs')->onDelete('cascade');
            $table->string('weight')->nullable();
            $table->string('pcs')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_out_details');
    }
};
