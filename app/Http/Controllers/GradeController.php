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
    public function index($studentId, $classSubjectId, Request $request)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);
        $semesters = SemesterYear::all();

        // set default semester
        $selectedSemesterYearId = $request->input('semester', SemesterYear::where('semester', 1)->first()->id);
        $semesterYear = SemesterYear::findOrFail($selectedSemesterYearId);

        // menagmbil data dari nilai pengetahuan, student is, clas subject id, semester year id
        $knowledgeScores = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();

        // menghotung nilai rata rata nilai
        $averageKnowledgeScore = $knowledgeScores->avg('score');


        // menambil data nilai sikap
        $attitudeScores = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();
        $averageAttitudeScore = $attitudeScores->avg('score');


        // mengambil data dari nilai keterampilan
        $skillScores = SkillScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();
        $averageSkillScore = $skillScores->avg('score');


        // memperbaharui tabel grade
        $grade = Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterYearId
            ],
            [
                'average_knowledge_score' => $averageKnowledgeScore,
                'average_attitude_score' => $averageAttitudeScore,
                'average_skill_score' => $averageSkillScore
            ]
        );

        // menentukan grade berdasarkan nilai
        $knowledgeGrade = $this->calculateGrade($averageKnowledgeScore, 'score');
        $attitudeGrade = $this->calculateGrade($averageAttitudeScore, 'score');
        $skillGrade = $this->calculateGrade($averageSkillScore, 'score');


        return view('grade.index', compact(
            'student',
            'classSubject',
            'semesterYear',
            'knowledgeScores',
            'averageKnowledgeScore',
            'knowledgeGrade',
            'attitudeScores',
            'averageAttitudeScore',
            'attitudeGrade',
            'skillScores',
            'averageSkillScore',
            'skillGrade',
            'semesters',
            'selectedSemesterYearId'
        ));
    }

    //Nilai Pengetahuan
    public function showDetailKnowledgeScore($studentId, $classSubjectId, Request $request)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);
        $semesters = SemesterYear::all();

        $selectedSemesterYearId = $request->input('semester', SemesterYear::where('semester', 1)->first()->id);
        $semesterYear = SemesterYear::findOrFail($selectedSemesterYearId);


        // menambil data dari salah satu tabel
        $assessmentTypes = KnowledgeScore::distinct()->pluck('assessment_type');

        $knowledgeScores = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();

        return view('grade.detailKnowledgeScore', compact(
            'student',
            'classSubject',
            'semesterYear',
            'knowledgeScores',
            'semesters',
            'assessmentTypes',
            'selectedSemesterYearId'
        ));
    }

    public function editKnowledgeScore($studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);
        $semesterYear = SemesterYear::findOrFail($semesterYearId);

        $knowledgeScore = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYear->id)
            ->where('assessment_type', $assessmentType)
            ->first();

        return view('grade.editKnowledgeScore', compact('student', 'classSubject', 'semesterYear', 'knowledgeScore', 'assessmentType'));
    }

    public function updateKnowledgeScore(Request $request, $studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'semesterYearId' => 'required|exists:semester_years,id'
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

        // Perbarui tabel grade
        $averageKnowledgeScore = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->avg('final_score');

        $grade = Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $semesterYearId,
            ],
            [
                'final_score' => $averageKnowledgeScore,
                'grade' => $this->calculateGrade($averageKnowledgeScore, 'score')
            ]
        );

        // Perbarui atau buat data di tabel Rapor
        Rapor::updateOrCreate(
            [
                'student_id' => $studentId,
                'semester_year_id' => $semesterYearId,
                'class_subject_id' => $classSubjectId,
            ],
            [
                'grade_id' => $grade->id,
                'school_name' => 'SDN DAWUAN',
                'school_address' => 'KP Pasir Eurih',
            ]
        );

        // notifikasi
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

    //Nilai Sikap
    public function showDetailAttitudeScore($studentId, $classSubjectId, Request $request)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);
        $semesters = SemesterYear::all();
        $selectedSemesterYearId = $request->input('semester', SemesterYear::where('semester', 1)->first()->id);
        $semesterYear = SemesterYear::findOrFail($selectedSemesterYearId);
        $assessmentTypes = AttitudeScore::distinct()->pluck('assessment_type');
        $attitudeScores = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();


        $grade = Grade::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->first();

        return view('grade.detailAttitudeScore', compact(
            'student',
            'classSubject',
            'semesterYear',
            'attitudeScores',
            'semesters',
            'assessmentTypes',
            'selectedSemesterYearId',
            'grade'
        ));
    }


    public function editAttitudeScore($studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);
        $semesterYear = SemesterYear::findOrFail($semesterYearId);
        $attitudeScore = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYear->id)
            ->where('assessment_type', $assessmentType)
            ->first();
        return view('grade.editAttitudeScore', compact('student', 'classSubject', 'semesterYear', 'attitudeScore', 'assessmentType'));
    }

    public function updateAttitudeScore(Request $request, $studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'semesterYearId' => 'required|exists:semester_years,id'
        ]);

        $score = $request->input('score');

        $attitudeScore = AttitudeScore::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId,
            'assessment_type' => $assessmentType
        ]);

        if (!$attitudeScore->exists) {
            $previousAttitudeScore = AttitudeScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->orderBy('id', 'desc')
                ->first();

            $previousFinalScore = $previousAttitudeScore ? $previousAttitudeScore->final_score : 0;

            $attitudeScore->score = $score;
            $attitudeScore->final_score = $previousFinalScore + $score;
            $attitudeScore->description = $request->input('description');
            $attitudeScore->save();
        } else {
            $previousFinalScore = $attitudeScore->final_score;

            $scoreDifference = $score - $attitudeScore->score;

            $attitudeScore->score = $score;
            $attitudeScore->final_score = max(0, $attitudeScore->final_score + $scoreDifference);
            $attitudeScore->description = $request->input('description');
            $attitudeScore->save();

            $attitudeScoresBelow = AttitudeScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->where('id', '>', $attitudeScore->id)
                ->orderBy('id')
                ->get();

            foreach ($attitudeScoresBelow as $attitude) {
                $previousFinalScore += $attitude->score + $scoreDifference;
                $attitude->final_score = max(0, $previousFinalScore);
                $attitude->grade = $this->calculateGrade($attitude->final_score, 'final_score');
                $attitude->save();
            }

        }

        $averageAttitudeScore = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->avg('final_score');

        $grade = Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $semesterYearId,
            ],
            [
                'final_score' => $averageAttitudeScore,
                'grade' => $this->calculateGrade($averageAttitudeScore, 'score')
            ]
        );

        Rapor::updateOrCreate(
            [
                'student_id' => $studentId,
                'semester_year_id' => $semesterYearId,
                'class_subject_id' => $classSubjectId,
            ],
            [
                'grade_id' => $grade->id,
                'school_name' => 'SDN DAWUAN',
                'school_address' => 'KP Pasir Eurih',
            ]
        );

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

    public function editSkillScore($studentId, $classSubjectId, $semesterYearId, $assessmentType)
        {
            $student = Student::findOrFail($studentId);
            $classSubject = ClassSubject::findOrFail($classSubjectId);
            $semesterYear = SemesterYear::findOrFail($semesterYearId);

            $skillScore = SkillScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYear->id)
                ->where('assessment_type', $assessmentType)
                ->first();

            return view('grade.editSkillScore', compact(
                'student',
                'classSubject',
                'semesterYear',
                'skillScore',
                'assessmentType'
            ));
        }

        public function updateSkillScore(Request $request, $studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        $score = $request->input('score');

        $skillScore = SkillScore::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId,
            'assessment_type' => $assessmentType
        ]);

        if (!$skillScore->exists) {
            $previousSkillScore = SkillScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->orderBy('id', 'desc')
                ->first();

            $previousFinalScore = $previousSkillScore ? $previousSkillScore->final_score : 0;

            $skillScore->score = $score;
            $skillScore->final_score = $previousFinalScore + $score;
            $skillScore->description = $request->input('description');
            $skillScore->grade = $this->calculateGrade($skillScore->final_score, 'final_score');
            $skillScore->save();
        } else {
            $previousFinalScore = $skillScore->final_score;

            $scoreDifference = $score - $skillScore->score;

            $skillScore->score = $score;
            $skillScore->final_score = max(0, $skillScore->final_score + $scoreDifference);
            $skillScore->grade = $this->calculateGrade($skillScore->final_score, 'final_score');
            $skillScore->description = $request->input('description');
            $skillScore->save();

            $skillScoresBelow = SkillScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->where('id', '>', $skillScore->id)
                ->orderBy('id')
                ->get();

            foreach ($skillScoresBelow as $skill) {
                $previousFinalScore += $skill->score + $scoreDifference;
                $skill->final_score = max(0, $previousFinalScore);
                $skill->grade = $this->calculateGrade($skill->final_score, 'final_score');
                $skill->save();
            }
        }

        $averageSkillScore = SkillScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->avg('final_score');

        $grade = Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $semesterYearId,
            ],
            [
                'final_score' => $averageSkillScore,
                'grade' => $this->calculateGrade($averageSkillScore, 'score')
            ]
        );

        Rapor::updateOrCreate(
            [
                'student_id' => $studentId,
                'semester_year_id' => $semesterYearId,
                'class_subject_id' => $classSubjectId,
            ],
            [
                'grade_id' => $grade->id,
                'school_name' => 'SDN DAWUAN',
                'school_address' => 'KP Pasir Eurih',
            ]
        );

        $notification = [
            'alert-type' => 'success',
            'message' => 'Data Penilaian Berhasil Disimpan'
        ];
        return redirect()->route('grade.detailSkillScore', [
            'studentId' => $studentId,
            'classSubjectId' => $classSubjectId,
            'semesterYearId' => $semesterYearId
        ])->with($notification);
    }

    //Sistem Pembuat Keputusan
    private function calculateGrade($value, $type)
    {
        $value = max(0, min(100, $value));

        // Periksa jenis nilai
        if ($type == 'score') {
            if ($value >= 90) {
                return 'A';
            } elseif ($value >= 80) {
                return 'B';
            } elseif ($value >= 70) {
                return 'C';
            } else {
                return 'D';
            }
        } elseif ($value) {
            if ($value >= 90) {
                return 'A';
            } elseif ($value >= 80) {
                return 'B';
            } elseif ($value >= 70) {
                return 'C';
            } else {
                return 'D';
            }
        } else {
            return '-';
        }
    }

}
