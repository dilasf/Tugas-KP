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
            Schema::create('grades', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('class_subject_id');
                $table->unsignedBigInteger('semester_year_id');
                $table->unsignedBigInteger('knowledge_score_id');
                $table->unsignedBigInteger('attitude_score_id');
                $table->unsignedBigInteger('skill_score_id');
                $table->timestamps();

                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
                $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
                $table->foreign('semester_year_id')->references('id')->on('semester_years');
                $table->foreign('knowledge_score_id')->references('id')->on('knowledge_scores')->onDelete('cascade');
                $table->foreign('attitude_score_id')->references('id')->on('attitude_scores')->onDelete('cascade');
                $table->foreign('skill_score_id')->references('id')->on('skill_scores')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
