<?php

namespace Database\Seeders;

use App\Models\ClassSubject;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Pastikan ada data di tabel classes dan subjects terlebih dahulu
        $class = StudentClass::first();
        $subject = Subject::first();

        if ($class && $subject) {
            ClassSubject::create([
                'class_id' => $class->id,
                'subject_id' => $subject->id,
            ]);
        }
    }
}
