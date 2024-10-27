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
        Schema::create('classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('class_name', 50)->unique();
            $table->integer('level',1);
            $table->integer('number_of_male_students',2)->default(0);
            $table->integer('number_of_female_students',2)->default(0);
            $table->integer('number_of_students',2)->default(0);
            $table->unsignedBigInteger('homeroom_teacher_id');
            $table->string('curriculum',25);
            $table->string('room', 20);
            $table->timestamps();

            $table->foreign('homeroom_teacher_id')->references('id')->on('teachers');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
