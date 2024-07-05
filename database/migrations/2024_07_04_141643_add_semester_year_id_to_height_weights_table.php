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
        Schema::table('height_weights', function (Blueprint $table) {
            $table->unsignedBigInteger('semester_year_id')->nullable()->after('weight');

            // Add foreign key constraint if needed
            $table->foreign('semester_year_id')->references('id')->on('semester_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('height_weights', function (Blueprint $table) {
            $table->dropForeign(['semester_year_id']);
            $table->dropColumn('semester_year_id');
        });
    }
};
