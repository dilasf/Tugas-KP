<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Rapor;
use App\Models\SemesterYear;
use App\Models\Student;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    private function calculateGrade($value)
    {
        if ($value >= 90) {
            return 'A';
        } elseif ($value >= 80) {
            return 'B';
        } elseif ($value >= 70) {
            return 'C';
        } else {
            return 'D';
        }
    }

    public function index(Request $request, $id)
    {
        $selectedSemesterYearId = $request->input('semester_year_id', null);
        $semesters = SemesterYear::all();
        $sidebarOpen = false;

        // Tentukan semester default berdasarkan waktu saat ini
        $currentMonth = now()->month;
        if ($currentMonth >= 1 && $currentMonth <= 6) {
            $defaultSemester = $semesters->firstWhere('semester', 1);
        } else {
            $defaultSemester = $semesters->firstWhere('semester', 2);
        }

        if (!$selectedSemesterYearId) {
            $selectedSemesterYearId = $defaultSemester->id;
        }

        $student = Student::findOrFail($id);

        $grades = Grade::where('student_id', $id)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();

        // Hitung grade untuk setiap grade dan simpan ke database
        $grades->each(function($grade) {
            $gradeKnowledge = $this->calculateGrade($grade->average_knowledge_score);
            $gradeAttitude = $this->calculateGrade($grade->average_attitude_score);
            $gradeSkill = $this->calculateGrade($grade->average_skill_score);

            // Save the grades to the model
            $grade->gradeKnowledge = $gradeKnowledge;
            $grade->gradeAttitude = $gradeAttitude;
            $grade->gradeSkill = $gradeSkill;
            $grade->save();
        });

        $rapors = Rapor::whereIn('grade_id', $grades->pluck('id'))->get();

        // Load relationships
        $rapors->load('grade.classSubject.subject', 'grade.semesterYear');

        return view('rapors.index', compact('student', 'rapors', 'semesters', 'selectedSemesterYearId', 'sidebarOpen'));
    }

    // public function index(Request $request, $id)
    // {
    //     $selectedSemesterYearId = $request->input('semester_year_id', null);
    //     $semesters = SemesterYear::all();

    //     // Tentukan semester default berdasarkan waktu saat ini
    //     $currentMonth = now()->month;
    //     if ($currentMonth >= 1 && $currentMonth <= 6) {
    //         // Bulan Januari sampai Juni -> Semester 1
    //         $defaultSemester = $semesters->firstWhere('semester', 1);
    //     } else {
    //         // Bulan Juli sampai Desember -> Semester 2
    //         $defaultSemester = $semesters->firstWhere('semester', 2);
    //     }

    //     // Jika tidak ada semester yang dipilih, atur semester default sesuai waktu saat ini
    //     if (!$selectedSemesterYearId) {
    //         $selectedSemesterYearId = $defaultSemester->id;
    //     }

    //     // Ambil data siswa
    //     $student = Student::findOrFail($id);

    //     // Ambil data nilai berdasarkan semester yang dipilih
    //     $grades = Grade::where('student_id', $id)
    //         ->when($selectedSemesterYearId, function ($query, $selectedSemesterYearId) {
    //             return $query->where('semester_year_id', $selectedSemesterYearId);
    //         })
    //         ->distinct()
    //         ->get();

    //     // Load relasi grade
    //     // $grades->load('gradeKnowledge');

    //     // Load data rapor
    //     $rapors = Rapor::whereIn('grade_id', $grades->pluck('id'))->get();

    //     // Load relasi untuk menampilkan nama kelas dan mata pelajaran
    //     $rapors->load('grade.classSubject.subject');

    //     return view('rapors.index', compact('student', 'rapors', 'semesters', 'selectedSemesterYearId'));
    // }
}
