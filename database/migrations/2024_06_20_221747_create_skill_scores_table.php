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
        Schema::create('skill_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('teacher_id');
            // $table->string('teacher_type');
            $table->string('assessment_type', 100);
            // $table->unsignedBigInteger('attendance_id')->nullable();
            $table->integer('score')->nullable();
            $table->integer('final_score')->nullable();
            $table->string('grade', 1)->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            // $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_scores');
    }
};
