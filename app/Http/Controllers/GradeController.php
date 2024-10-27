<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttitudeScore;
use App\Models\Student;
use App\Models\ClassSubject;
use App\Models\Grade;
use App\Models\KnowledgeScore;
use App\Models\Rapor;
use App\Models\SemesterYear;
use App\Models\SkillScore;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
   // Menampilkan nilai rata-rata
public function index($studentId, $classSubjectId, Request $request)
{

    $student = Student::findOrFail($studentId);
    $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);

    // Mengambil tahun dan bulan saat ini
    $currentYear = Carbon::now()->year;
    $currentMonth = Carbon::now()->month;

    // Menentukan semester berdasarkan bulan
    $currentSemester = ($currentMonth >= 1 && $currentMonth <= 6) ? 1 : 2;

    // Mendapatkan semester yang sesuai dengan tahun ini
    $semesters = SemesterYear::where('year', $currentYear)->get();

    // Mengatur nilai default semester yang dipilih
    $defaultSemester = SemesterYear::where('year', $currentYear)
        ->where('semester', $currentSemester)
        ->first();

    $selectedSemesterYearId = $request->get('semester', $defaultSemester->id);

    // Mengatur session sesuai dengan semester yang dipilih
    session(['selectedSemesterYearId' => $selectedSemesterYearId]);

    // Cek apakah semester_year_id valid
    $semesterYear = SemesterYear::find($selectedSemesterYearId);
    if (!$semesterYear) {
        return redirect()->back()->withErrors(['semester' => 'Semester yang dipilih tidak valid.']);
    }

    // Cek apakah sudah ada entry di tabel grades untuk siswa, mata pelajaran, dan semester yang dipilih
    $grade = Grade::where('student_id', $studentId)
    ->where('class_subject_id', $classSubjectId)
    ->where('semester_year_id', $selectedSemesterYearId)
    ->where('teacher_id', Auth::user()->teacher_id)
    ->first();


    // Jika belum ada, buat entry baru di tabel grades
    if (!$grade) {
        $grade = new Grade();
        $grade->student_id = $studentId;
        $grade->class_subject_id = $classSubjectId;
        $grade->semester_year_id = $selectedSemesterYearId;
        $grade->teacher_id = Auth::user()->teacher_id; // Set teacher_id sesuai dengan user yang terautentikasi
        $grade->save();
    }

    // Menghitung rata-rata nilai jika ada nilai di tabel grade
    $averageKnowledgeScore = round($grade->average_knowledge_score ?? 0);
    $averageAttitudeScore = round($grade->average_attitude_score ?? 0);
    $averageSkillScore = round($grade->average_skill_score ?? 0);

    // Menentukan grade berdasarkan rata-rata nilai
    $knowledgeGrade = $this->calculateGrade($averageKnowledgeScore);
    $attitudeGrade = $this->calculateGrade($averageAttitudeScore);
    $skillGrade = $this->calculateGrade($averageSkillScore);

    return view('grade.index', compact(
        'student', 'classSubject', 'semesters', 'selectedSemesterYearId',
        'grade', 'averageKnowledgeScore', 'averageAttitudeScore',
        'averageSkillScore', 'knowledgeGrade', 'attitudeGrade', 'skillGrade'
    ));
}

    //Nilai Pengetahuan
    // public function detailKnowledgeScore(Request $request, $studentId, $classSubjectId)
    // {
    //     $student = Student::findOrFail($studentId);
    //     $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
    //     $semesters = SemesterYear::all();
    //     $selectedSemesterYearId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

    //     // Find the grade for the student, class subject, and semester
    //     $grade = Grade::where('student_id', $studentId)
    //         ->where('class_subject_id', $classSubjectId)
    //         ->where('semester_year_id', $selectedSemesterYearId)
    //         ->where('teacher_id', Auth::user()->teacher_id)
    //         ->first();

    //     if (!$grade) {
    //         $knowledgeScores = collect();

    //         // Fetch assessment types based on teacher_id if grade is null
    //         $assessmentTypes = KnowledgeScore::whereHas('grade', function ($query) {
    //             $query->where('teacher_id', Auth::user()->teacher_id);
    //         })
    //             ->distinct()
    //             ->pluck('assessment_type');

    //         $assessmentTypes = KnowledgeScore::where('teacher_id', Auth::user()->teacher_id)
    //                                                 ->distinct()
    //                                                 ->pluck('assessment_type');
    //     } else {
    //         $knowledgeScores = KnowledgeScore::where('grade_id', $grade->id)->get();

    //         // Fetch assessment types based on grade_id if grade exists
    //         $assessmentTypes = KnowledgeScore::where('grade_id', $grade->id)
    //             ->distinct()
    //             ->pluck('assessment_type');

    //         // If no SkillScore entries have the grade_id, fallback to teacher_id
    //         if ($knowledgeScores->isEmpty()) {
    //             $assessmentTypes = KnowledgeScore::where('teacher_id', Auth::user()->teacher_id)
    //                                         ->distinct()
    //                                         ->pluck('assessment_type');
    //         }

    //     }

    //     return view('grade.detailKnowledgeScore',
    //     compact('student',
    //     'classSubject',
    //     'semesters',
    //     'selectedSemesterYearId',
    //     'knowledgeScores',
    //     'grade',
    //     'assessmentTypes',
    //     'studentId', 'classSubjectId'));
    // }
    public function detailKnowledgeScore(Request $request, $studentId, $classSubjectId)
{
    $student = Student::findOrFail($studentId);
    $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
    $semesters = SemesterYear::all();
    $selectedSemesterYearId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

    // Find the grade for the student, class subject, and semester
    $grade = Grade::where('student_id', $studentId)
        ->where('class_subject_id', $classSubjectId)
        ->where('semester_year_id', $selectedSemesterYearId)
        ->where('teacher_id', Auth::user()->teacher_id)
        ->first();

    // Fetch all distinct assessment types for the teacher
    $assessmentTypes = KnowledgeScore::where('teacher_id', Auth::user()->teacher_id)
        ->distinct()
        ->pluck('assessment_type');

    $knowledgeScores = $grade ? KnowledgeScore::where('grade_id', $grade->id)->get() : collect();

    return view('grade.detailKnowledgeScore', compact(
        'student',
        'classSubject',
        'semesters',
        'selectedSemesterYearId',
        'knowledgeScores',
        'grade',
        'assessmentTypes',
        'studentId',
        'classSubjectId'
    ));
}


    public function editKnowledgeScore(Request $request, $studentId, $classSubjectId, $assessmentType)
    {
        $student = Student::find($studentId);
        $classSubject = ClassSubject::with('subject', 'class')->find($classSubjectId);
        $selectedSemesterId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

        $grade = Grade::firstOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterId,
                'teacher_id' => Auth::user()->teacher_id
            ]
        );

         // Find or create the skill score for the given grade and assessment type
    $knowledgeScore = KnowledgeScore::firstOrNew(
        [
            'grade_id' => $grade->id,
            'assessment_type' => $assessmentType,
            'teacher_id' => Auth::user()->teacher_id
        ]
    );

        $semesters = SemesterYear::all();

        return view('grade.editKnowledgeScore',
        compact('student', 'classSubject',
        'knowledgeScore', 'assessmentType',
        'selectedSemesterId', 'grade', 'semesters'));
    }

    public function updateKnowledgeScore(Request $request, $studentId, $classSubjectId, $assessmentType)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        $selectedSemesterId = session('selectedSemesterYearId', 1);

        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterId)
            ->first();

            if (!$grade) {
                return redirect()->route('grade.detailAttitudeScore', [
                    'studentId' => $studentId,
                    'classSubjectId' => $classSubjectId,
                    'semester' => $selectedSemesterId
                ])->with('error', 'Grade tidak ditemukan.');
            }


        // Cari atau buat KnowledgeScore berdasarkan grade_id dan assessmentType
        $knowledgeScore = KnowledgeScore::updateOrCreate(
            [
                'grade_id' => $grade->id,
                'assessment_type' => $assessmentType,
                'teacher_id' => Auth::user()->teacher_id
            ],
            [
                'score' => $request->input('score'),
                'description' => $request->input('description'),
            ]
        );

        // Hitung ulang final_score berdasarkan total nilai sebelumnya
        $knowledgeScores = KnowledgeScore::where('grade_id', $grade->id)
            ->orderBy('id')
            ->get();

        $previousFinalScore = 0;

        foreach ($knowledgeScores as $score) {
            $previousFinalScore += $score->score;
            $score->final_score = $previousFinalScore;
            $score->grade = $this->calculateGrade($score->score, 'score');
            $score->save();
        }

        // Update grade table
        $averageKnowledgeScore = $knowledgeScores->avg('score');

        // Perbarui atau buat rekaman Grade
        $grade->average_knowledge_score = $averageKnowledgeScore;
        $grade->gradeKnowledge = $this->calculateGrade($averageKnowledgeScore, 'score');
        $grade->save();

        // Perbarui atau buat rekaman Rapor
        Rapor::updateOrCreate(
            [
                'grade_id' => $grade->id,
            ],
            [
                'school_name' => 'SDN DAWUAN',
                'school_address' => 'KP Pasir Eurih',
            ]
        );

        return redirect()->route('grade.detailKnowledgeScore', [
            'studentId' => $studentId,
            'classSubjectId' => $classSubjectId,
            'semester' => $selectedSemesterId
        ])->with([
            'message' => 'Nilai Pengetahuan Berhasil Piperbarui.',
            'alert-type' => 'success'
        ]);

    }


    //Nilai Sikap
    // public function detailAttitudeScore(Request $request, $studentId, $classSubjectId)
    // {
    //     $student = Student::findOrFail($studentId);
    //     $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
    //     $semesters = SemesterYear::all();
    //     $selectedSemesterYearId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

    //     // Find the grade for the student, class subject, and semester
    //     $grade = Grade::where('student_id', $studentId)
    //         ->where('class_subject_id', $classSubjectId)
    //         ->where('semester_year_id', $selectedSemesterYearId)
    //         ->where('teacher_id', Auth::user()->teacher_id)
    //         ->first();

    //     if (!$grade) {
    //         $attitudeScores = collect();

    //         // Fetch assessment types based on teacher_id if grade is null
    //         $assessmentTypes = AttitudeScore::whereHas('grade', function ($query) {
    //             $query->where('teacher_id', Auth::user()->teacher_id);
    //         })
    //             ->distinct()
    //             ->pluck('assessment_type');

    //         $assessmentTypes = AttitudeScore::where('teacher_id', Auth::user()->teacher_id)
    //                                                 ->distinct()
    //                                                 ->pluck('assessment_type');
    //     } else {
    //         $attitudeScores = AttitudeScore::where('grade_id', $grade->id)->get();

    //         // Fetch assessment types based on grade_id if grade exists
    //         $assessmentTypes = AttitudeScore::where('grade_id', $grade->id)
    //             ->distinct()
    //             ->pluck('assessment_type');

    //         // If no SkillScore entries have the grade_id, fallback to teacher_id
    //         if ($attitudeScores->isEmpty()) {
    //             $assessmentTypes = AttitudeScore::where('teacher_id', Auth::user()->teacher_id)
    //                                         ->distinct()
    //                                         ->pluck('assessment_type');
    //         }
    //     }

    //     return view('grade.detailAttitudeScore',
    //     compact('student', 'classSubject',
    //     'semesters', 'selectedSemesterYearId',
    //     'attitudeScores', 'grade', 'assessmentTypes',
    //     'studentId', 'classSubjectId'));
    // }
    public function detailAttitudeScore(Request $request, $studentId, $classSubjectId)
{
    $student = Student::findOrFail($studentId);
    $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
    $semesters = SemesterYear::all();
    $selectedSemesterYearId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

    // Find the grade for the student, class subject, and semester
    $grade = Grade::where('student_id', $studentId)
        ->where('class_subject_id', $classSubjectId)
        ->where('semester_year_id', $selectedSemesterYearId)
        ->where('teacher_id', Auth::user()->teacher_id)
        ->first();

    // Fetch all distinct assessment types for the teacher
    $assessmentTypes = AttitudeScore::where('teacher_id', Auth::user()->teacher_id)
        ->distinct()
        ->pluck('assessment_type');

    $attitudeScores = $grade ? AttitudeScore::where('grade_id', $grade->id)->get() : collect();

    return view('grade.detailAttitudeScore', compact(
        'student',
        'classSubject',
        'semesters',
        'selectedSemesterYearId',
        'attitudeScores',
        'grade',
        'assessmentTypes',
        'studentId',
        'classSubjectId'
    ));
}


    public function editAttitudeScore(Request $request, $studentId, $classSubjectId, $assessmentType)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
        $selectedSemesterId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

        // Find or create the grade for the student, class subject, and semester
        $grade = Grade::firstOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterId,
                'teacher_id' => Auth::user()->teacher_id
            ]
        );

         // Find or create the skill score for the given grade and assessment type
    $attitudeScore = AttitudeScore::firstOrNew(
        [
            'grade_id' => $grade->id,
            'assessment_type' => $assessmentType,
            'teacher_id' => Auth::user()->teacher_id
        ]
    );

        $semesters = SemesterYear::all();

        return view('grade.editAttitudeScore',
        compact('student', 'classSubject',
        'attitudeScore', 'assessmentType',
        'selectedSemesterId', 'grade', 'semesters'));
    }

    public function updateAttitudeScore(Request $request, $studentId, $classSubjectId, $assessmentType)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        $selectedSemesterId = session('selectedSemesterYearId', 1);

        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterId)
            ->first();

            if (!$grade) {
                return redirect()->route('grade.detailAttitudeScore', [
                    'studentId' => $studentId,
                    'classSubjectId' => $classSubjectId,
                    'semester' => $selectedSemesterId
                ])->with('error', 'Grade tidak ditemukan.');
            }


        $attitudeScore = AttitudeScore::updateOrCreate(
            [
                'grade_id' => $grade->id,
                'assessment_type' => $assessmentType,
                'teacher_id' => Auth::user()->teacher_id
            ],
            [
                'score' => $request->input('score'),
                'description' => $request->input('description'),
            ]
        );

        $attitudeScores = AttitudeScore::where('grade_id', $grade->id)
            ->orderBy('id')
            ->get();

        $previousFinalScore = 0;

        foreach ($attitudeScores as $score) {
            $previousFinalScore += $score->score;
            $score->final_score = $previousFinalScore;
            $score->grade = $this->calculateGrade($score->score, 'score');
            $score->save();
        }

        $averageAttitudeScore = $attitudeScores->avg('score');

        $grade->average_attitude_score = $averageAttitudeScore;
        $grade->gradeAttitude = $this->calculateGrade($averageAttitudeScore, 'score');
        $grade->save();

        Rapor::updateOrCreate(
            [
                'grade_id' => $grade->id,
            ],
            [
                'school_name' => 'SDN DAWUAN',
                'school_address' => 'KP Pasir Eurih',
            ]
        );

        return redirect()->route('grade.detailAttitudeScore', [
            'studentId' => $studentId,
            'classSubjectId' => $classSubjectId,
            'semester' => $selectedSemesterId
        ])->with([
            'message' => 'Nilai Sikap Berhasil Diperbarui.',
            'alert-type' => 'success'
        ]);

    }



    //Nilai Keterampilan
    // public function detailSkillScore(Request $request, $studentId, $classSubjectId)
    // {
    //     $student = Student::findOrFail($studentId);
    //     $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
    //     $semesters = SemesterYear::all();
    //     $selectedSemesterYearId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

    //     // Find the grade for the student, class subject, and semester
    //     $grade = Grade::where('student_id', $studentId)
    //         ->where('class_subject_id', $classSubjectId)
    //         ->where('semester_year_id', $selectedSemesterYearId)
    //         ->where('teacher_id', Auth::user()->teacher_id)
    //         ->first();

    //     if (!$grade) {
    //         $skillScores = collect();
    //         $attendance = new Attendance([
    //             'sick' => 0,
    //             'permission' => 0,
    //             'unexcused' => 0,
    //         ]);

    //         // Fetch assessment types based on teacher_id if grade is null
    //         $assessmentTypes = SkillScore::whereHas('grade', function ($query) {
    //             $query->where('teacher_id', Auth::user()->teacher_id);
    //         })
    //         ->distinct()
    //         ->pluck('assessment_type');
    //         $assessmentTypes = SkillScore::where('teacher_id', Auth::user()->teacher_id)
    //         ->distinct()
    //         ->pluck('assessment_type');
    //     } else {
    //         $skillScores = SkillScore::where('grade_id', $grade->id)->get();
    //         $attendance = Attendance::firstOrNew([
    //             'student_id' => $studentId,
    //             'class_subject_id' => $classSubjectId,
    //             'semester_year_id' => $selectedSemesterYearId,
    //         ]);

    //         // Fetch assessment types based on grade_id if grade exists
    //         $assessmentTypes = SkillScore::where('grade_id', $grade->id)
    //             ->distinct()
    //             ->pluck('assessment_type');

    //         // If no SkillScore entries have the grade_id, fallback to teacher_id
    //         if ($skillScores->isEmpty()) {
    //             $assessmentTypes = SkillScore::where('teacher_id', Auth::user()->teacher_id)
    //                                         ->distinct()
    //                                         ->pluck('assessment_type');
    //         }
    //     }

    //     return view('grade.detailSkillScore', compact(
    //         'student',
    //         'classSubject',
    //         'semesters',
    //         'selectedSemesterYearId',
    //         'skillScores',
    //         'attendance',
    //         'assessmentTypes',
    //         'studentId',
    //         'classSubjectId'
    //     ));
    // }
    public function detailSkillScore(Request $request, $studentId, $classSubjectId)
{
    $student = Student::findOrFail($studentId);
    $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
    $semesters = SemesterYear::all();
    $selectedSemesterYearId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

    // Find the grade for the student, class subject, and semester
    $grade = Grade::where('student_id', $studentId)
        ->where('class_subject_id', $classSubjectId)
        ->where('semester_year_id', $selectedSemesterYearId)
        ->where('teacher_id', Auth::user()->teacher_id)
        ->first();

    // Fetch all distinct assessment types for the teacher
    $assessmentTypes = SkillScore::where('teacher_id', Auth::user()->teacher_id)
        ->distinct()
        ->pluck('assessment_type');

    // Initialize skill scores and attendance
    $skillScores = collect();
    $attendance = new Attendance([
        'sick' => 0,
        'permission' => 0,
        'unexcused' => 0,
    ]);

    if ($grade) {
        // Fetch skill scores if grade exists
        $skillScores = SkillScore::where('grade_id', $grade->id)->get();

        // Get attendance information
        $attendance = Attendance::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $selectedSemesterYearId,
        ]);

        // If no skill scores entries have the grade_id, fallback to teacher_id
        if ($skillScores->isEmpty()) {
            $assessmentTypes = SkillScore::where('teacher_id', Auth::user()->teacher_id)
                ->distinct()
                ->pluck('assessment_type');
        }
    }

    return view('grade.detailSkillScore', compact(
        'student',
        'classSubject',
        'semesters',
        'selectedSemesterYearId',
        'skillScores',
        'attendance',
        'assessmentTypes',
        'studentId',
        'classSubjectId'
    ));
}


    public function editSkillScore(Request $request, $studentId, $classSubjectId, $assessmentType)
{
    $student = Student::findOrFail($studentId);
    $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
    $selectedSemesterId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

    // Find or create the grade for the student, class subject, and semester
    $grade = Grade::firstOrCreate(
        [
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $selectedSemesterId,
            'teacher_id' => Auth::user()->teacher_id
        ]
    );

    // Find or create the skill score for the given grade and assessment type
    $skillScore = SkillScore::firstOrNew(
        [
            'grade_id' => $grade->id,
            'assessment_type' => $assessmentType,
            'teacher_id' => Auth::user()->teacher_id
        ]
    );

    $semesters = SemesterYear::all();

    return view('grade.editSkillScore', compact('student', 'classSubject', 'skillScore', 'assessmentType', 'selectedSemesterId', 'grade', 'semesters'));
}



public function updateSkillScore(Request $request, $studentId, $classSubjectId, $assessmentType)
{
    $request->validate([
        'score' => 'required|numeric|min:0|max:100',
        'description' => 'nullable|string|max:255',
    ]);

    $selectedSemesterId = session('selectedSemesterYearId', 1);

    // Find the grade for the student, class subject, and semester
    $grade = Grade::where('student_id', $studentId)
        ->where('class_subject_id', $classSubjectId)
        ->where('semester_year_id', $selectedSemesterId)
        ->where('teacher_id', Auth::user()->teacher_id)
        ->first();

    if (!$grade) {
        return redirect()->route('grade.detailSkillScore', [
            'studentId' => $studentId,
            'classSubjectId' => $classSubjectId,
            'semester' => $selectedSemesterId
        ])->with('error', 'Grade tidak ditemukan.');
    }

    // Update or create the skill score for the given grade and assessment type
    $skillScore = SkillScore::updateOrCreate(
        [
            'grade_id' => $grade->id,
            'assessment_type' => $assessmentType,
            'teacher_id' => Auth::user()->teacher_id
        ],
        [
            'score' => $request->input('score'),
            'description' => $request->input('description'),
        ]
    );

    // Update other related calculations or data as needed
    $skillScores = SkillScore::where('grade_id', $grade->id)
        ->orderBy('id')
        ->get();

    $previousFinalScore = 0;

    foreach ($skillScores as $score) {
        $previousFinalScore += $score->score;
        $score->final_score = $previousFinalScore;
        $score->grade = $this->calculateGrade($score->score, 'score');
        $score->save();
    }

    $averageSkillScore = $skillScores->avg('score');

    $grade->average_skill_score = $averageSkillScore;
    $grade->gradeSkill = $this->calculateGrade($averageSkillScore, 'score');
    $grade->save();

    Rapor::updateOrCreate(
        [
            'grade_id' => $grade->id,
        ],
        [
            'school_name' => 'SDN DAWUAN',
            'school_address' => 'KP Pasir Eurih',
        ]
    );


    return redirect()->route('grade.detailSkillScore', [
        'studentId' => $studentId,
        'classSubjectId' => $classSubjectId,
        'semester' => $selectedSemesterId
    ])->with([
        'message' => 'Nilai Keterampilan Berhasil Diperbarui.',
        'alert-type' => 'success'
    ]);
}


    //Sistem Pembuat Keputusan
    private function calculateGrade($score)
{
    $score = max(0, min(100, $score));

    if ($score >= 90) {
        return 'A';
    } elseif ($score >= 80) {
        return 'B';
    } elseif ($score >= 70) {
        return 'C';
    } elseif ($score >= 60) {
        return 'D';
    } else {
        return 'E';
    }
}


}
