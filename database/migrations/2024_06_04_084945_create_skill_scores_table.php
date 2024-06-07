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
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('class_subject_id')->nullable();
            $table->unsignedBigInteger('semester_year_id')->nullable();
            $table->string('assessment_type', 100);
            $table->unsignedBigInteger('attendance_id')->nullable();
            $table->integer('score')->default(0);
            $table->integer('final_score')->default(0);
            $table->string('grade', 1)->default('D');
            $table->string('description', 255)->nullable()->default('No description');
            $table->timestamps();

            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
            $table->foreign('semester_year_id')->references('id')->on('semester_years');
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