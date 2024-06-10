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
        Schema::create('rapors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('grade_id');
            $table->string('school_name', 255)->default('SDN DAWUAN');
            $table->string('school_address', 255)->default('KP Pasir Eurih');
            $table->unsignedBigInteger('class_subject_id')->nullable();
            $table->string('suggestion', 255)->nullable();
            $table->unsignedBigInteger('semester_year_id');
            $table->unsignedBigInteger('health_id')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->unsignedBigInteger('extracurricular_id')->nullable();
            $table->date('print_date')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('grade_id')->references('id')->on('grades');
            $table->foreign('health_id')->references('id')->on('healths');
            $table->foreign('activity_id')->references('id')->on('achievements');
            $table->foreign('extracurricular_id')->references('id')->on('extracurriculars');
            $table->foreign('semester_year_id')->references('id')->on('semester_years');
            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapors');
    }
};
