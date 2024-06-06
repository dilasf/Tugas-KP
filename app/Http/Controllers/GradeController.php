<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassSubject;
use App\Models\Grade;
use App\Models\KnowledgeScore;
use App\Models\SemesterYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    //Mnemapilkan Total Nilai
    public function index(Request $request, $studentId, $classSubjectId)
{
    // Ambil nilai semester yang dipilih dari request, atau gunakan nilai default jika tidak ada
    $selectedSemesterYearId = $request->input('semester_year_id', null);
    $semesters = SemesterYear::all();

    // Tentukan semester default
    $defaultSemester = $semesters->firstWhere('semester', 1); // Mengambil semester dengan nomor 1

    // Jika tidak ada semester yang dipilih, atur semester default ke semester 1
    if (!$selectedSemesterYearId) {
        $selectedSemesterYearId = $defaultSemester->id;
    }

    // Ambil data berdasarkan studentId dan classSubjectId
    $student = Student::findOrFail($studentId);
    $classSubject = ClassSubject::findOrFail($classSubjectId);

    //sidebar
    $sidebarOpen = false;

    // Ambil nilai knowledge score berdasarkan studentId, classSubjectId, dan semesterYearId
    $knowledgeScores = KnowledgeScore::where('student_id', $studentId)
        ->where('class_subject_id', $classSubjectId)
        ->when($selectedSemesterYearId, function ($query, $selectedSemesterYearId) {
            return $query->where('semester_year_id', $selectedSemesterYearId);
        })
        ->get();

    // Hitung total nilai akhir
    $finalScore = $knowledgeScores->sum('final_score');

    // Hitung grade berdasarkan nilai akhir
    $grade = $this->calculateGrade($finalScore, 'final_score');

    // Kembalikan view dengan data yang diperlukan
    return view('grade.index', compact('sidebarOpen', 'finalScore', 'grade', 'selectedSemesterYearId', 'semesters', 'student', 'classSubject'));
}


    // Detail Nilai Knowlage, Atittude, Skill
    public function showDetail($studentId, $classSubjectId, $semesterYearId = null)
    {
        // Retrieve the student and classSubject based on IDs
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);
        $sidebarOpen = false;

        // Retrieve all semesters
        $semesters = SemesterYear::all();

        // If semesterYearId is not provided, set it to the first semester's ID
        if (is_null($semesterYearId)) {
            $semesterYearId = $semesters->first()->id;
        }

        // Retrieve the selected semester
        $semester = SemesterYear::findOrFail($semesterYearId);

        // Ambil nilai unik dari kolom yang sesuai dengan jenis penilaian
        $assessmentTypes = KnowledgeScore::distinct()->pluck('assessment_type');

        // Retrieve knowledge scores based on student ID, class subject ID, and semesterYearId
        $knowledgeScores = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->get();

        // Return the view with the necessary data
        return view('grade.detail', compact('sidebarOpen', 'student', 'classSubject', 'semester', 'knowledgeScores', 'semesterYearId', 'semesters', 'assessmentTypes'));
    }


    //Edit Nilai Knowledge
    public function edit($studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {

        $knowledgeScore = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->where('assessment_type', $assessmentType)
            ->first();

        $defaultSemesterYearId = $semesterYearId;

        return view('grade.edit', compact('knowledgeScore', 'defaultSemesterYearId', 'assessmentType', 'studentId', 'classSubjectId'));
    }

    public function update(Request $request, $studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        // Lakukan validasi
        $request->validate([
            'score' => 'required|numeric|max:100',
            'description' => 'nullable|string',
        ]);

        // Mendapatkan nilai yang diinput
        $score = $request->input('score');
        if ($score > 100) {
            return back()->withErrors(['score' => 'Nilai tidak boleh melebihi 100']);
        }

        // Cari atau buat data nilai dari database
        $knowledgeScore = KnowledgeScore::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId,
            'assessment_type' => $assessmentType
        ]);

        // Ambil final_score dari penilaian sebelumnya (jika ada)
        $previousFinalScore = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->where('id', '<', $knowledgeScore->id)
            ->orderBy('id', 'desc')
            ->pluck('final_score')
            ->first();

        // Jika nilai sebelumnya sudah ada, kurangi nilai lama dari Final Score
        if ($knowledgeScore->exists) {
            $knowledgeScore->final_score -= $knowledgeScore->score;
        }

        // Tambahkan nilai baru ke Final Score, ditambah dengan final_score sebelumnya
        $knowledgeScore->score = $score;
        $knowledgeScore->final_score = ($previousFinalScore ?? 0) + $score;

        // Mendapatkan nilai grade baru berdasarkan nilai akhir baru
        $knowledgeScore->grade = $this->calculateGrade($score, 'score');

        // Simpan perubahan ke dalam database
        $knowledgeScore->description = $request->input('description');
        $knowledgeScore->save();

        $notification = [
            'alert-type' => 'success',
            'message' => 'Data Penilaian Berhasil Disimpan'
        ];
        return redirect()->route('grade.detail', [
            'studentId' => $studentId,
            'classSubjectId' => $classSubjectId,
            'semesterYearId' => $semesterYearId
        ])->with($notification);
    }



private function calculateGrade($value, $type)
{
    // Periksa jenis nilai
    if ($type == 'score') {
        // Kondisi pengkodisian berdasarkan score
        if ($value >= 80) {
            return 'A';
        } elseif ($value >= 70) {
            return 'B';
        } elseif ($value >= 60) {
            return 'C';
        } else {
            return 'D';
        }
    } elseif ($type == 'final_score') {
        // Kondisi pengkodisian berdasarkan final_score
        if ($value >= 80) {
            return 'A';
        } elseif ($value >= 70) {
            return 'B';
        } elseif ($value >= 60) {
            return 'C';
        } else {
            return 'D';
        }
    } else {
        // Kondisi default jika tipe tidak dikenali
        return 'Tidak Valid';
    }
}

}
