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
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('photo')->nullable();
            $table->bigInteger('nuptk')->nullable()->unique();
            $table->string('teacher_name');
            $table->string('gender', 12);
            $table->string('placeOfbirth', 100);
            $table->date('dateOfbirth');
            $table->string('religion', 10);
            $table->string('address');
            $table->string('mail', 50);
            $table->bigInteger('mobile_phone')->nullable()->unique();
            $table->bigInteger('nip')->nullable()->unique();
            $table->string('employment_status', 50);
            $table->string('typesOfCAR', 50);
            $table->string('prefix', 30)->nullable();
            $table->string('suffix', 20)->nullable();
            $table->string('education_Level', 50);
            $table->string('fieldOfStudy', 100);
            $table->string('certification', 255)->nullable();
            $table->date('startDateofEmployment');
            $table->string('additional_Duties', 100)->nullable();
            $table->string('teaching', 150)->nullable();
            $table->string('competency', 100)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
