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
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('class_subject_id')->nullable();
            $table->unsignedBigInteger('semester_year_id')->nullable();
            $table->float('average_knowledge_score')->nullable()->default(0);
            $table->string('gradeKnowledge', 1)->nullable()->default('D');
            $table->string('descriptionKnowledge', 255)->nullable()->default('Tidak Ada Deskripsi');
            $table->float('average_attitude_score')->nullable()->default(0);
            $table->string('gradeAttitude', 1)->nullable()->default('D');
            $table->string('descriptionAttitude', 255)->nullable()->default('Tidak Ada Deskripsi');
            $table->float('average_skill_score')->nullable()->default(0);
            $table->string('gradeSkill', 1)->nullable()->default('D');
            $table->string('descriptionSkill', 255)->nullable()->default('Tidak Ada Deskripsi');
            $table->timestamps();

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
        Schema::dropIfExists('grades');
    }
};
