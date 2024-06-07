<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttitudeScore;
use App\Models\Student;
use App\Models\ClassSubject;
use App\Models\Grade;
use App\Models\KnowledgeScore;
use App\Models\SemesterYear;
use App\Models\SkillScore;
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
        $defaultSemester = $semesters->firstWhere('semester', 1);

        // Jika tidak ada semester yang dipilih, atur semester default ke semester 1
        if (!$selectedSemesterYearId) {
            $selectedSemesterYearId = $defaultSemester->id;
        }

        // Ambil data berdasarkan studentId dan classSubjectId
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);

        // Sidebar
        $sidebarOpen = false;

        // Ambil nilai knowledge score berdasarkan studentId, classSubjectId, dan semesterYearId
        $knowledgeScores = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->when($selectedSemesterYearId, function ($query, $selectedSemesterYearId) {
                return $query->where('semester_year_id', $selectedSemesterYearId);
            })
            ->get();

        // Ambil nilai attitude score berdasarkan studentId, classSubjectId, dan semesterYearId
        $attitudeScores = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->when($selectedSemesterYearId, function ($query, $selectedSemesterYearId) {
                return $query->where('semester_year_id', $selectedSemesterYearId);
            })
            ->get();

        // Ambil nilai skill score berdasarkan studentId, classSubjectId, dan semesterYearId
        $skillScores = SkillScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->when($selectedSemesterYearId, function ($query, $selectedSemesterYearId) {
                return $query->where('semester_year_id', $selectedSemesterYearId);
            })
            ->get();

        // Hitung total nilai akhir untuk setiap kategori
        $finalKnowledgeScore = $knowledgeScores->sum('final_score');
        $finalAttitudeScore = $attitudeScores->sum('final_score');
        $finalSkillScore = $skillScores->sum('final_score');

        // Hitung grade berdasarkan nilai akhir
        $knowledgeGrade = $this->calculateGrade($finalKnowledgeScore, 'final_score');
        $attitudeGrade = $this->calculateGrade($finalAttitudeScore, 'final_score');
        $skillGrade = $this->calculateGrade($finalSkillScore, 'final_score');

        // Kembalikan view dengan data yang diperlukan
        return view('grade.index', compact(
            'sidebarOpen',
            'finalKnowledgeScore',
            'knowledgeGrade',
            'finalAttitudeScore',
            'attitudeGrade',
            'finalSkillScore',
            'skillGrade',
            'selectedSemesterYearId',
            'semesters',
            'student',
            'classSubject',
            'knowledgeScores',
            'attitudeScores',
            'skillScores'
        ));
    }


    // Detail Nilai Knowlage
    public function showDetailKnowledgeScore($studentId, $classSubjectId, $semesterYearId = null)
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
        return view('grade.detailKnowledgeScore', compact(
            'sidebarOpen',
            'student',
            'classSubject',
            'semester',
            'knowledgeScores',
            'semesterYearId',
            'semesters',
            'assessmentTypes'
        ));
    }

    //Edit Nilai Knowledge
    public function editKnowledgeScore($studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {

        $knowledgeScore = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->where('assessment_type', $assessmentType)
            ->first();

        $defaultSemesterYearId = $semesterYearId;

        return view('grade.editKnowledgeScore', compact(
            'knowledgeScore',
            'defaultSemesterYearId',
            'assessmentType',
            'studentId',
            'classSubjectId'));
    }

    public function updateKnowledgeScore(Request $request, $studentId, $classSubjectId, $semesterYearId, $assessmentType)
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

        // Jika knowledgeScore belum ada di database, maka ini adalah input pertama
        if (!$knowledgeScore->exists) {
            $knowledgeScore->score = $score;
            $knowledgeScore->final_score = $score; // Set final_score menjadi score pertama kali
            $knowledgeScore->grade = $this->calculateGrade($score, 'score');
            $knowledgeScore->description = $request->input('description');
            $knowledgeScore->save();
        } else {
            // Jika knowledgeScore sudah ada, maka ini adalah update
            // Ambil final_score dari penilaian sebelumnya (jika ada)
            $previousFinalScore = KnowledgeScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->where('id', '<', $knowledgeScore->id)
                ->orderBy('id', 'desc')
                ->pluck('final_score')
                ->first();

            // Kurangi nilai lama dari Final Score
            $knowledgeScore->final_score -= $knowledgeScore->score;

            // Tambahkan nilai baru ke Final Score, ditambah dengan final_score sebelumnya
            $knowledgeScore->score = $score;
            $knowledgeScore->final_score = ($previousFinalScore ?? 0) + $score;

            // Mendapatkan nilai grade baru berdasarkan nilai akhir baru
            $knowledgeScore->grade = $this->calculateGrade($score, 'score');

            // Simpan perubahan ke dalam database
            $knowledgeScore->description = $request->input('description');
            $knowledgeScore->save();
        }

        $notification = [
            'alert-type' => 'success',
            'message' => 'Data Penilaian Berhasil Disimpan'
        ];
        return redirect()->route('grade.detailKnowledgeScore', [
            'studentId' => $studentId,
            'classSubjectId' => $classSubjectId,
            'semesterYearId' => $semesterYearId
        ])->with($notification);
    }

    // Detail Nilai Sikap
    public function showDetailAttitudeScore($studentId, $classSubjectId, $semesterYearId = null)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);
        $sidebarOpen = false;

        $semesters = SemesterYear::all();

        if (is_null($semesterYearId)) {
            $semesterYearId = $semesters->first()->id;
        }

        $semester = SemesterYear::findOrFail($semesterYearId);
        $assessmentTypes = AttitudeScore::distinct()->pluck('assessment_type');

        $attitudeScores = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->get();

        return view('grade.detailAttitudeScore', compact(
            'sidebarOpen',
            'student',
            'classSubject',
            'semester',
            'attitudeScores',
            'semesterYearId',
            'semesters',
            'assessmentTypes'
        ));
    }

    // Edit Nilai Sikap
    public function editAttitudeScore($studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        $attitudeScore = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->where('assessment_type', $assessmentType)
            ->first();

        $defaultSemesterYearId = $semesterYearId;

        return view('grade.editAttitudeScore', compact(
            'attitudeScore',
            'defaultSemesterYearId',
            'assessmentType',
            'studentId',
            'classSubjectId'
        ));
    }

    public function updateAttitudeScore(Request $request, $studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        $request->validate([
            'score' => 'required|numeric|max:100',
            'description' => 'nullable|string',
        ]);

        $score = $request->input('score');
        if ($score > 100) {
            return back()->withErrors(['score' => 'Nilai tidak boleh melebihi 100']);
        }

        $attitudeScore = AttitudeScore::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId,
            'assessment_type' => $assessmentType
        ]);

        if (!$attitudeScore->exists) {
            $attitudeScore->score = $score;
            $attitudeScore->final_score = $score;
            $attitudeScore->grade = $this->calculateGrade($score, 'score');
            $attitudeScore->description = $request->input('description');
            $attitudeScore->save();
        } else {
            $previousFinalScore = AttitudeScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->where('id', '<', $attitudeScore->id)
                ->orderBy('id', 'desc')
                ->pluck('final_score')
                ->first();

            $attitudeScore->final_score -= $attitudeScore->score;
            $attitudeScore->score = $score;
            $attitudeScore->final_score = ($previousFinalScore ?? 0) + $score;
            $attitudeScore->grade = $this->calculateGrade($score, 'score');
            $attitudeScore->description = $request->input('description');
            $attitudeScore->save();
        }

        $notification = [
            'alert-type' => 'success',
            'message' => 'Data Penilaian Berhasil Disimpan'
        ];
        return redirect()->route('grade.detailAttitudeScore', [
            'studentId' => $studentId,
            'classSubjectId' => $classSubjectId,
            'semesterYearId' => $semesterYearId
        ])->with($notification);
    }

    // Detail Nilai Keterampilan
    public function showDetailSkillScore($studentId, $classSubjectId, $semesterYearId = null)
{
    $student = Student::findOrFail($studentId);
    $classSubject = ClassSubject::findOrFail($classSubjectId);
    $sidebarOpen = false;

    $semesters = SemesterYear::all();

    if (is_null($semesterYearId)) {
        $semesterYearId = $semesters->first()->id;
    }

    $semester = SemesterYear::findOrFail($semesterYearId);
    $assessmentTypes = SkillScore::distinct()->pluck('assessment_type');

    // Ambil data keterampilan dan kehadiran
    $skillScores = SkillScore::where('student_id', $studentId)
        ->where('class_subject_id', $classSubjectId)
        ->where('semester_year_id', $semesterYearId)
        ->get();

    // Ambil data kehadiran atau buat instance kosong jika tidak ditemukan
    $attendance = Attendance::firstOrNew(
        [
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId,
        ],
        [
            'sick' => 0,
            'permission' => 0,
            'unexcused' => 0,
        ]
    );

    return view('grade.detailSkillScore', compact(
        'sidebarOpen',
        'student',
        'classSubject',
        'semester',
        'skillScores',
        'semesterYearId',
        'semesters',
        'assessmentTypes',
        'attendance'
    ));
}

    // Edit Nilai Keterampilan
    public function editSkillScore($studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        $skillScore = SkillScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->where('assessment_type', $assessmentType)
            ->first();

        $defaultSemesterYearId = $semesterYearId;

        return view('grade.editSkillScore', compact(
            'skillScore',
            'defaultSemesterYearId',
            'assessmentType',
            'studentId',
            'classSubjectId'
        ));
    }

    public function updateSkillScore(Request $request, $studentId, $classSubjectId, $semesterYearId, $assessmentType)
{
    $request->validate([
        'score' => 'required|numeric|max:100',
        'description' => 'nullable|string',
        'sick' => 'nullable|numeric',
        'permission' => 'nullable|numeric',
        'unexcused' => 'nullable|numeric',
    ]);

    $score = $request->input('score');
    if ($score > 100) {
        return back()->withErrors(['score' => 'Nilai tidak boleh melebihi 100']);
    }

    $skillScore = SkillScore::firstOrNew([
        'student_id' => $studentId,
        'class_subject_id' => $classSubjectId,
        'semester_year_id' => $semesterYearId,
        'assessment_type' => $assessmentType
    ]);

    if (!$skillScore->exists) {
        $skillScore->score = $score;
        $skillScore->final_score = $score;
        $skillScore->grade = $this->calculateGrade($score, 'score');
        $skillScore->description = $request->input('description');
        $skillScore->save();
    } else {
        $previousFinalScore = SkillScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->where('id', '<', $skillScore->id)
            ->orderBy('id', 'desc')
            ->pluck('final_score')
            ->first();

        $skillScore->final_score -= $skillScore->score;
        $skillScore->score = $score;
        $skillScore->final_score = ($previousFinalScore ?? 0) + $score;
        $skillScore->grade = $this->calculateGrade($score, 'score');
        $skillScore->description = $request->input('description');
        $skillScore->save();
    }

    // Update atau tambahkan informasi kehadiran
    $attendance = Attendance::updateOrCreate(
        ['student_id' => $studentId, 'class_subject_id' => $classSubjectId, 'semester_year_id' => $semesterYearId],
        [
            'sick' => $request->input('sick', 0),
            'permission' => $request->input('permission', 0),
            'unexcused' => $request->input('unexcused', 0),
        ]
    );

    $notification = [
        'alert-type' => 'success',
        'message' => 'Data Penilaian Keterampilan dan Kehadiran Berhasil Disimpan'
    ];
    return redirect()->route('grade.detailSkillScore', [
        'studentId' => $studentId,
        'classSubjectId' => $classSubjectId,
        'semesterYearId' => $semesterYearId
    ])->with($notification);
}


private function calculateGrade($value, $type)
{
    // Periksa jenis nilai
    if ($type == 'score') {
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
        return 'Tidak Valid';
    }
}

}
