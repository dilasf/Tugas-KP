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
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_subject_id');
            $table->unsignedBigInteger('semester_year_id');
            $table->unsignedBigInteger('rapor_id')->nullable();
            $table->integer('sick')->nullable()->default(0);
            $table->integer('permission')->nullable()->default(0);
            $table->integer('unexcused')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
            $table->foreign('semester_year_id')->references('id')->on('semester_years');
            $table->foreign('rapor_id')->references('id')->on('rapors')->onDelete('cascade'); // Define rapor_id as foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
