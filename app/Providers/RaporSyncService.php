<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Health;
use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Rapor;

class RaporSyncService
{
    public static function sync()
    {
        // Ambil semua data siswa yang memiliki nilai lengkap
        $students = Grade::whereNotNull('average_knowledge_score')
                        ->whereNotNull('average_attitude_score')
                        ->whereNotNull('average_skill_score')
                        ->pluck('student_id')
                        ->unique();

        // Loop melalui setiap siswa dan lakukan sinkronisasi rapor jika belum ada
        foreach ($students as $studentId) {
            // Cek apakah data rapor untuk siswa ini sudah ada
            $existingRapor = Rapor::where('student_id', $studentId)->first();

            if (!$existingRapor) {
                // Ambil data grade siswa
                $grade = Grade::where('student_id', $studentId)->first();

                // Cek apakah data kesehatan, prestasi, dan ekstrakurikuler sudah ada
                $health = Health::where('student_id', $studentId)->first();
                $achievement = Achievement::where('student_id', $studentId)->first();
                $extracurricular = Extracurricular::where('student_id', $studentId)->first();

                // Tambahkan data ke tabel rapor
                Rapor::create([
                    'student_id' => $studentId,
                    'grade_id' => $grade->id,
                    'school_name' => 'SDN DAWUAN', // atau ambil dari pengaturan
                    'school_address' => 'KP Pasir Eurih', // atau ambil dari pengaturan
                    'suggestion' => $grade->suggestion ?? 'Tidak Ada Saran', // contoh saja, sesuaikan dengan kebutuhan
                    'health_id' => $health ? $health->id : null,
                    'activity_id' => $achievement ? $achievement->id : null,
                    'extracurricular_id' => $extracurricular ? $extracurricular->id : null,
                    'print_date' => now(),
                ]);
            }
        }
    }
}
