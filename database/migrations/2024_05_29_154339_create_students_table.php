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
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('student_photo', 255)->nullable();
            $table->boolean('status')->default(true);
            $table->bigInteger('nis')->unique();
            $table->bigInteger('nisn')->unique();
            $table->bigInteger('nipd');
            $table->unsignedBigInteger('class_id');
            $table->string('student_name', 255);
            $table->string('gender', 10);
            $table->bigInteger('nik')->unique();
            $table->string('place_of_birth', 50);
            $table->date('date_of_birth');
            $table->string('religion', 10);
            $table->string('address', 255);
            $table->boolean('special_needs')->default(false);
            $table->string('previous_school', 255)->nullable();
            // $table->unsignedBigInteger('height_weight_id');
            $table->string('birth_certificate_number', 60)->unique();
            $table->string('residence_type', 25);
            $table->unsignedBigInteger('guardian_id');
            $table->bigInteger('no_kk')->unique();
            $table->integer('child_number');
            $table->integer('number_of_siblings')->nullable();
            $table->string('transportation', 20);
            $table->integer('distance_to_school');
            $table->timestamps();

            // Foreign keys
            $table->foreign('class_id')->references('id')->on('classes');
            // $table->foreign('height_weight_id')->references('id')->on('height_weights')->onDelete('cascade');
            $table->foreign('guardian_id')->references('id')->on('guardians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
