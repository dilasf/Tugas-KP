<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('father_name', 255)->nullable();
            $table->string('mother_name', 255)->nullable();
            $table->bigInteger('father_nik',17)->nullable()->unique();
            $table->bigInteger('mother_nik',17)->nullable()->unique();
            $table->date('father_birth_year')->nullable();
            $table->date('mother_birth_year')->nullable();
            $table->string('father_education', 255)->nullable();
            $table->string('mother_education', 255)->nullable();
            $table->string('father_occupation', 255)->nullable();
            $table->string('mother_occupation', 255)->nullable();
            $table->string('father_income', 100)->nullable();
            $table->string('mother_income', 100)->nullable();
            $table->bigInteger('parent_phone_number', 13)->nullable()->unique();
            $table->string('parent_email', 255)->nullable()->unique();
            $table->string('guardian_name', 255)->nullable();
            $table->bigInteger('guardian_nik',17)->nullable()->unique();
            $table->date('guardian_birth_year')->nullable();
            $table->string('guardian_education', 255)->nullable();
            $table->string('guardian_occupation', 255)->nullable();
            $table->string('guardian_income', 100)->nullable();
            $table->bigInteger('guardian_phone_number',13)->nullable()->unique();
            $table->string('guardian_email', 255)->nullable()->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
