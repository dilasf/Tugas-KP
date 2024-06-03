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
        Schema::create('height_weights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('head_size')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('height_weight');
    }
};
