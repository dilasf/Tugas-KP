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
        Schema::create('healths', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rapor_id')->nullable();
            $table->string('hearing', 150)->nullable();
            $table->string('vision', 150)->nullable();
            $table->string('tooth', 150)->nullable();
            $table->timestamps();

            $table->foreign('rapor_id')->references('id')->on('rapors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('healths');
    }
};
