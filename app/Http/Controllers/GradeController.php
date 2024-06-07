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
    // //Mnemapilkan Total Nilai
    // public function index(Request $request, $studentId, $classSubjectId)
    // {
    //     // Ambil nilai semester yang dipilih dari request, atau gunakan nilai default jika tidak ada
    //     $selectedSemesterYearId = $request->input('semester_year_id', null);
    //     $semesters = SemesterYear::all();

    //     // Tentukan semester default
    //     $defaultSemester = $semesters->firstWhere('semester', 1);

    //     // Jika tidak ada semester yang dipilih, atur semester default ke semester 1
    //     if (!$selectedSemesterYearId) {
    //         $selectedSemesterYearId = $defaultSemester->id;
    //     }

    //     // Ambil data berdasarkan studentId dan classSubjectId
    //     $student = Student::findOrFail($studentId);
    //     $classSubject = ClassSubject::findOrFail($classSubjectId);

    //     // Sidebar
    //     $sidebarOpen = false;

    //     // Ambil nilai knowledge score berdasarkan studentId, classSubjectId, dan semesterYearId
    //     $knowledgeScores = KnowledgeScore::where('student_id', $studentId)
    //         ->where('class_subject_id', $classSubjectId)
    //         ->when($selectedSemesterYearId, function ($query, $selectedSemesterYearId) {
    //             return $query->where('semester_year_id', $selectedSemesterYearId);
    //         })
    //         ->get();

    //     // Ambil nilai attitude score berdasarkan studentId, classSubjectId, dan semesterYearId
    //     $attitudeScores = AttitudeScore::where('student_id', $studentId)
    //         ->where('class_subject_id', $classSubjectId)
    //         ->when($selectedSemesterYearId, function ($query, $selectedSemesterYearId) {
    //             return $query->where('semester_year_id', $selectedSemesterYearId);
    //         })
    //         ->get();

    //     // Ambil nilai skill score berdasarkan studentId, classSubjectId, dan semesterYearId
    //     $skillScores = SkillScore::where('student_id', $studentId)
    //         ->where('class_subject_id', $classSubjectId)
    //         ->when($selectedSemesterYearId, function ($query, $selectedSemesterYearId) {
    //             return $query->where('semester_year_id', $selectedSemesterYearId);
    //         })
    //         ->get();

    //     // Hitung total nilai akhir untuk setiap kategori
    //     $finalKnowledgeScore = $knowledgeScores->sum('final_score');
    //     $finalAttitudeScore = $attitudeScores->sum('final_score');
    //     $finalSkillScore = $skillScores->sum('final_score');

    //     // Hitung grade berdasarkan nilai akhir
    //     $knowledgeGrade = $this->calculateGrade($finalKnowledgeScore, 'final_score');
    //     $attitudeGrade = $this->calculateGrade($finalAttitudeScore, 'final_score');
    //     $skillGrade = $this->calculateGrade($finalSkillScore, 'final_score');

    //     // Kembalikan view dengan data yang diperlukan
    //     return view('grade.index', compact(
    //         'sidebarOpen',
    //         'finalKnowledgeScore',
    //         'knowledgeGrade',
    //         'finalAttitudeScore',
    //         'attitudeGrade',
    //         'finalSkillScore',
    //         'skillGrade',
    //         'selectedSemesterYearId',
    //         'semesters',
    //         'student',
    //         'classSubject',
    //         'knowledgeScores',
    //         'attitudeScores',
    //         'skillScores'
    //     ));
    // }

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

    // Hitung jumlah penilaian yang telah diisi untuk setiap kategori
    $knowledgeAssessmentCount = $knowledgeScores->count();
    $attitudeAssessmentCount = $attitudeScores->count();
    $skillAssessmentCount = $skillScores->count();

    // Hitung total nilai akhir untuk setiap kategori
    $finalKnowledgeScore = $knowledgeScores->sum('score') / ($knowledgeAssessmentCount > 0 ? $knowledgeAssessmentCount : 1);
    $finalAttitudeScore = $attitudeScores->sum('score') / ($attitudeAssessmentCount > 0 ? $attitudeAssessmentCount : 1);
    $finalSkillScore = $skillScores->sum('score') / ($skillAssessmentCount > 0 ? $skillAssessmentCount : 1);

    // Hitung grade berdasarkan nilai akhir
    $knowledgeGrade = $this->calculateGrade($finalKnowledgeScore, 'score');
    $attitudeGrade = $this->calculateGrade($finalAttitudeScore, 'score');
    $skillGrade = $this->calculateGrade($finalSkillScore, 'score');

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
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        // Mendapatkan nilai yang diinput
        $score = $request->input('score');

        // Cari atau buat data nilai dari database
        $knowledgeScore = KnowledgeScore::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId,
            'assessment_type' => $assessmentType
        ]);

        // Jika knowledgeScore belum ada di database, maka ini adalah input pertama
        if (!$knowledgeScore->exists) {
            // Ambil final_score dari penilaian sebelumnya (jika ada)
            $previousKnowledgeScore = KnowledgeScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->orderBy('id', 'desc')
                ->first();

            $previousFinalScore = $previousKnowledgeScore ? $previousKnowledgeScore->final_score : 0;

            // Set nilai final_score
            $knowledgeScore->score = $score;
            $knowledgeScore->final_score = $previousFinalScore + $score;
            $knowledgeScore->grade = $this->calculateGrade($knowledgeScore->final_score, 'final_score');
            $knowledgeScore->description = $request->input('description');
            $knowledgeScore->save();
        } else {
            // Jika knowledgeScore sudah ada, maka ini adalah update
            // Ambil nilai akhir sebelumnya
            $previousFinalScore = $knowledgeScore->final_score;

            // Hitung selisih antara nilai baru dan nilai lama
            $scoreDifference = $score - $knowledgeScore->score;

            // Perbarui nilai akhir dan grade untuk knowledgeScore yang diubah
            $knowledgeScore->score = $score;
            $knowledgeScore->final_score = max(0, $knowledgeScore->final_score + $scoreDifference);
            $knowledgeScore->grade = $this->calculateGrade($knowledgeScore->final_score, 'final_score');
            $knowledgeScore->description = $request->input('description');
            $knowledgeScore->save();

            // Perbarui nilai akhir untuk semua nilai yang berada di bawah knowledgeScore yang diubah
            $knowledgeScoresBelow = KnowledgeScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->where('id', '>', $knowledgeScore->id)
                ->orderBy('id')
                ->get();

            foreach ($knowledgeScoresBelow as $knowledge) {
                $previousFinalScore += $knowledge->score + $scoreDifference;
                $knowledge->final_score = max(0, $previousFinalScore);
                $knowledge->grade = $this->calculateGrade($knowledge->final_score, 'final_score');
                $knowledge->save();
            }
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
        // Lakukan validasi
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        // Mendapatkan nilai yang diinput
        $score = $request->input('score');

        // Cari atau buat data nilai dari database
        $attitudeScore = AttitudeScore::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId,
            'assessment_type' => $assessmentType
        ]);

        if (!$attitudeScore->exists) {
            // Jika ini adalah input pertama
            $previousAttitudeScore = AttitudeScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->orderBy('id', 'desc')
                ->first();

            $previousFinalScore = $previousAttitudeScore ? $previousAttitudeScore->final_score : 0;

            // Set nilai final_score
            $attitudeScore->score = $score;
            $attitudeScore->final_score = $previousFinalScore + $score;
            $attitudeScore->grade = $this->calculateGrade($attitudeScore->final_score, 'final_score');
            $attitudeScore->description = $request->input('description');
            $attitudeScore->save();
        } else {
            // Jika attitudeScore sudah ada, maka ini adalah update
            // Ambil nilai akhir sebelumnya
            $previousFinalScore = $attitudeScore->final_score;

            // Hitung selisih antara nilai baru dan nilai lama
            $scoreDifference = $score - $attitudeScore->score;

            // Perbarui nilai akhir dan grade untuk attitudeScore yang diubah
            $attitudeScore->score = $score;
            $attitudeScore->final_score = max(0, $attitudeScore->final_score + $scoreDifference);
            $attitudeScore->grade = $this->calculateGrade($attitudeScore->final_score, 'final_score');
            $attitudeScore->description = $request->input('description');
            $attitudeScore->save();

            // Perbarui nilai akhir untuk semua nilai yang berada di bawah attitudeScore yang diubah
            $attitudeScoresBelow = AttitudeScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->where('id', '>', $attitudeScore->id)
                ->orderBy('id')
                ->get();

            $cumulativeScore = $attitudeScore->final_score;
            foreach ($attitudeScoresBelow as $attitude) {
                $cumulativeScore += $attitude->score + $scoreDifference;
                $attitude->final_score = max(0, $cumulativeScore);
                $attitude->grade = $this->calculateGrade($attitude->final_score, 'final_score');
                $attitude->save();
            }
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
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'sick' => 'nullable|numeric',
            'permission' => 'nullable|numeric',
            'unexcused' => 'nullable|numeric',
        ]);

        $score = $request->input('score');

        // Cari atau buat data nilai dari database
        $skillScore = SkillScore::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId,
            'assessment_type' => $assessmentType
        ]);

        if (!$skillScore->exists) {
            // Jika ini adalah input pertama
            $previousSkillScore = SkillScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->orderBy('id', 'desc')
                ->first();

            $previousFinalScore = $previousSkillScore ? $previousSkillScore->final_score : 0;

            // Set nilai final_score
            $skillScore->score = $score;
            $skillScore->final_score = $previousFinalScore + $score;
            $skillScore->grade = $this->calculateGrade($skillScore->final_score, 'final_score');
            $skillScore->description = $request->input('description');
            $skillScore->save();
        } else {
            // Jika skillScore sudah ada, maka ini adalah update
            // Ambil nilai akhir sebelumnya
            $previousFinalScore = $skillScore->final_score;

            // Hitung selisih antara nilai baru dan nilai lama
            $scoreDifference = $score - $skillScore->score;

            // Perbarui nilai akhir dan grade untuk skillScore yang diubah
            $skillScore->score = $score;
            $skillScore->final_score = max(0, $skillScore->final_score + $scoreDifference);
            $skillScore->grade = $this->calculateGrade($skillScore->final_score, 'final_score');
            $skillScore->description = $request->input('description');
            $skillScore->save();

            // Perbarui nilai akhir untuk semua nilai yang berada di bawah skillScore yang diubah
            $skillScoresBelow = SkillScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->where('id', '>', $skillScore->id)
                ->orderBy('id')
                ->get();

            $cumulativeScore = $skillScore->final_score;
            foreach ($skillScoresBelow as $skill) {
                $cumulativeScore += $skill->score + $scoreDifference;
                $skill->final_score = max(0, $cumulativeScore);
                $skill->grade = $this->calculateGrade($skill->final_score, 'final_score');
                $skill->save();
            }
        }

        // Update atau tambahkan informasi kehadiran
        Attendance::updateOrCreate(
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
