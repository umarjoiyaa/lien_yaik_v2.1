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
        Schema::create('drilling_reject_pelletes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dr_id')->nullable();
            $table->foreign('dr_id')->references('id')->on('drillings')->onDelete('cascade');
            $table->unsignedBigInteger('pellete_id')->nullable();
            $table->foreign('pellete_id')->references('id')->on('pelletes')->onDelete('cascade');
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
        Schema::dropIfExists('drilling_reject_pelletes');
    }
};
