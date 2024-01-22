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
        Schema::create('shotblast_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pellete_id')->nullable();
            $table->foreign('pellete_id')->references('id')->on('pelletes')->onDelete('cascade');
            $table->string('weight')->nullable();
            $table->string('pcs')->nullable();
            $table->unsignedBigInteger('sb_id')->nullable();
            $table->foreign('sb_id')->references('id')->on('shotblasts')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shotblast_details');
    }
};
