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
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('attendance_id')->nullable();
            $table->string('school_name', 255)->default('SDN DAWUAN');
            $table->string('school_address', 255)->default('KP Pasir Eurih');
            $table->string('social_attitudes', 255)->nullable();
            $table->string('spiritual_attitude', 255)->nullable();
            $table->string('suggestion', 255)->nullable();
            $table->date('print_date')->nullable();
            $table->string('status', 255)->nullable()->default('not_sent');
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('grades');
            $table->foreign('attendance_id')->references('id')->on('attendances');

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
