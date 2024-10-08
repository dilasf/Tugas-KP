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
        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rapor_id')->nullable();
            $table->string('activity', 200)->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->foreign('rapor_id')->references('id')->on('rapors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurriculars');
    }
};
