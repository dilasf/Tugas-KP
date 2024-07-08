<?php

namespace Database\Seeders;

use App\Models\SkillScore;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->get();

        // Iterasi setiap guru dan tambahkan SkillScore untuk mereka
        foreach ($teachers as $teacher) {
            SkillScore::create([
                'assessment_type' => 'Tugas 1',
                'teacher_id' => $teacher->teacher_id,
                'teacher_type' => 'App\Models\User', // Sesuaikan jika tipe guru berbeda
                'score' => 80, // Contoh nilai skor
                'final_score' => 85, // Contoh nilai skor akhir
                'grade' => 'A', // Contoh nilai grade
                'description' => 'Deskripsi penilaian', // Deskripsi opsional
            ]);
        }
    }
}
