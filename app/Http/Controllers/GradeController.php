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
use Illuminate\Http\Request;


class GradeController extends Controller
{
    //menampilkan nilai rata rata
    public function index($studentId, $classSubjectId, Request $request)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
        $semesters = SemesterYear::all();
        $selectedSemesterYearId = $request->get('semester', 1);

        //mengatur session sesuai dengan semester yg dipilih
        session(['selectedSemesterYearId' => $selectedSemesterYearId]);

        // Cek apakah sudah ada entry di tabel grades untuk siswa, mata pelajaran, dan semester yang dipilih
        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->first();

        // Jika belum ada, buat entry baru di tabel grades
        if (!$grade) {
            $grade = new Grade();
            $grade->student_id = $studentId;
            $grade->class_subject_id = $classSubjectId;
            $grade->semester_year_id = $selectedSemesterYearId;
            $grade->save();
        }

        // Menghitung rata-rata nilai jika ada nilai di tabel grade
        $averageKnowledgeScore = round($grade->average_knowledge_score ?? 0);
        $averageAttitudeScore = round($grade->average_attitude_score ?? 0);
        $averageSkillScore = round($grade->average_skill_score ?? 0);

        // Menentukan grade berdasarkan rata-rata nilai
        $knowledgeGrade = $this->calculateGrade($averageKnowledgeScore, 'score');
        $attitudeGrade = $this->calculateGrade($averageAttitudeScore, 'score');
        $skillGrade = $this->calculateGrade($averageSkillScore, 'score');

        return view('grade.index',
        compact('student',
        'classSubject',
        'semesters',
        'selectedSemesterYearId',
        'grade',
        'averageKnowledgeScore',
        'averageAttitudeScore',
        'averageSkillScore',
        'knowledgeGrade',
        'attitudeGrade',
        'skillGrade'));
    }

    //Nilai Pengetahuan
    public function detailKnowledgeScore(Request $request, $studentId, $classSubjectId)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
        $semesters = SemesterYear::all();
        $selectedSemesterYearId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->first();

        if (!$grade) {
            $knowledgeScores = collect();
        } else {
            $knowledgeScores = KnowledgeScore::where('grade_id', $grade->id)->get();
        }

        $assessmentTypes = KnowledgeScore::distinct()->pluck('assessment_type');

        return view('grade.detailKnowledgeScore',
        compact('student',
        'classSubject',
        'semesters',
        'selectedSemesterYearId',
        'knowledgeScores',
        'grade',
        'assessmentTypes',
        'studentId', 'classSubjectId'));
    }

    public function editKnowledgeScore(Request $request, $studentId, $classSubjectId, $assessmentType)
    {
        $student = Student::find($studentId);
        $classSubject = ClassSubject::with('subject', 'class')->find($classSubjectId);
        $selectedSemesterId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterId)
            ->firstOrCreate([
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterId
            ]);

        $knowledgeScore = KnowledgeScore::firstOrCreate(
            [
                'grade_id' => $grade->id,
                'assessment_type' => $assessmentType,
            ],
            [
                'score' => 0,
                'description' => ''
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
            abort(404, 'Grade not found for the specified student, class subject, and semester.');
        }

        // Cari atau buat KnowledgeScore berdasarkan grade_id dan assessmentType
        $knowledgeScore = KnowledgeScore::updateOrCreate(
            [
                'grade_id' => $grade->id,
                'assessment_type' => $assessmentType,
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
        ])->with('success', 'Nilai pengetahuan berhasil diperbarui.');
    }


    //Nilai Sikap
    public function detailAttitudeScore(Request $request, $studentId, $classSubjectId)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
        $semesters = SemesterYear::all();
        $selectedSemesterYearId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->first();

        if (!$grade) {
            $attitudeScores = collect();
        } else {
            $attitudeScores = AttitudeScore::where('grade_id', $grade->id)->get();
        }

        $assessmentTypes = AttitudeScore::distinct()->pluck('assessment_type');

        return view('grade.detailAttitudeScore',
        compact('student', 'classSubject',
        'semesters', 'selectedSemesterYearId',
        'attitudeScores', 'grade', 'assessmentTypes',
        'studentId', 'classSubjectId'));
    }

    public function editAttitudeScore(Request $request, $studentId, $classSubjectId, $assessmentType)
    {

        $student = Student::find($studentId);
        $classSubject = ClassSubject::with('subject', 'class')->find($classSubjectId);
        $selectedSemesterId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterId)
            ->firstOrCreate([
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterId
            ]);

        $attitudeScore = AttitudeScore::firstOrCreate(
            [
                'grade_id' => $grade->id,
                'assessment_type' => $assessmentType,
            ],
            [
                'score' => 0,
                'description' => 'Tidak Ada Deskripsi'
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
            abort(404, 'Grade not found for the specified student, class subject, and semester.');
        }

        $attitudeScore = AttitudeScore::updateOrCreate(
            [
                'grade_id' => $grade->id,
                'assessment_type' => $assessmentType,
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
        ])->with('success', 'Nilai sikap berhasil diperbarui.');
    }


    //Nilai Keterampilan
    public function showDetailSkillScore($studentId, $classSubjectId, Request $request)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);
        $semesters = SemesterYear::all();

        $selectedSemesterYearId = $request->input('semester', SemesterYear::where('semester', 1)->first()->id);
        $semesterYear = SemesterYear::findOrFail($selectedSemesterYearId);

        $assessmentTypes = SkillScore::distinct()->pluck('assessment_type');

        $skillScores = SkillScore::where('student_id', $studentId)
        ->where('class_subject_id', $classSubjectId)
        ->where('semester_year_id', $selectedSemesterYearId)
        ->get();

    return view('grade.detailSkillScore', compact(
        'student',
        'classSubject',
        'semesterYear',
        'skillScores',
        'semesters',
        'assessmentTypes',
        'selectedSemesterYearId'
    ));

    }

    public function detailSkillScore(Request $request, $studentId, $classSubjectId)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::with('subject', 'class')->findOrFail($classSubjectId);
        $semesters = SemesterYear::all();
        $selectedSemesterYearId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->first();

        if (!$grade) {
            $skillScores = collect();
            $attendance = new Attendance([
                'sick' => 0,
                'permission' => 0,
                'unexcused' => 0,
            ]);
        } else {
            $skillScores = SkillScore::where('grade_id', $grade->id)->get();
            $attendance = Attendance::firstOrNew([
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterYearId,
            ]);
        }

        $assessmentTypes = SkillScore::distinct()->pluck('assessment_type');

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
        $student = Student::find($studentId);
        $classSubject = ClassSubject::with('subject', 'class')->find($classSubjectId);
        $selectedSemesterId = $request->input('semester', $request->session()->get('selectedSemesterYearId', 1));

        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterId)
            ->firstOrCreate([
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterId
            ]);

        $skillScore = SkillScore::firstOrCreate(
            [
                'grade_id' => $grade->id,
                'assessment_type' => $assessmentType,
            ],
            [
                'score' => 0,
                'description' => 'Tidak Ada Deskripsi'
            ]
        );

        $semesters = SemesterYear::all();

        return view('grade.editSkillScore',
        compact('student', 'classSubject',
        'skillScore', 'assessmentType',
        'selectedSemesterId', 'grade', 'semesters'));
    }

    public function updateSkillScore(Request $request, $studentId, $classSubjectId, $assessmentType)
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

        $skillScore = SkillScore::updateOrCreate(
            [
                'grade_id' => $grade->id,
                'assessment_type' => $assessmentType,
            ],
            [
                'score' => $request->input('score'),
                'description' => $request->input('description'),
            ]
        );

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

        return redirect()->route('grade.detailSkillScore', [
            'studentId' => $studentId,
            'classSubjectId' => $classSubjectId,
            'semester' => $selectedSemesterId
        ])->with('success', 'Nilai keterampilan berhasil diperbarui.');
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
