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
        Schema::create('pelletes', function (Blueprint $table) {
            $table->id();
            $table->string('pellete_no')->nullable();
            $table->longText('qr_code')->nullable();
            $table->string('batch')->nullable();
            $table->string('weight')->nullable();
            $table->string('pcs')->nullable();
            $table->string('previous_batch')->nullable();
            $table->string('previous_weight')->nullable();
            $table->string('previous_pcs')->nullable();
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
        Schema::dropIfExists('pelletes');
    }
};
