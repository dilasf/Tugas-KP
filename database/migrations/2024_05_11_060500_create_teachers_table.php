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
        Schema::create('teachers', function (Blueprint $table) {
            $table->teacher_id();
            $table->string('photo') -> nullable();
            $table->integer('nuptk', 20);
            $table->string('teacher_name');
            $table->string('placeOfbirth', 100);
            $table->string('dateOfbirth', 50);
            $table->string('gender',12);
            $table->string('religion', 10);
            $table->string('addres');
            $table->integer('mobile_phone', 13) -> nullable();
            $table->boolean('status');
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
