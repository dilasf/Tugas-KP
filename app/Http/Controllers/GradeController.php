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
        // Retrieve student and class subject data
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);

        // Retrieve all semesters
        $semesters = SemesterYear::all();

        // Get the selected semester year ID, or set it to the first semester if none is selected
        $selectedSemesterYearId = $request->input('semester', SemesterYear::where('semester', 1)->first()->id);

        // Retrieve the details of the selected semester
        $semesterYear = SemesterYear::findOrFail($selectedSemesterYearId);

        // Retrieve knowledge scores for the student, class subject, and selected semester
        $knowledgeScores = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();

        // Calculate the average knowledge score
        $averageKnowledgeScore = $knowledgeScores->avg('score');

        // Update or create a Grade model for knowledge scores
        $knowledgeGrade = Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterYearId
            ],
            ['average_knowledge_score' => $averageKnowledgeScore]
        );

        // Calculate the grade for knowledge scores
        $knowledgeGrade = $this->calculateGrade($averageKnowledgeScore, 'score');

        // Retrieve attitude scores for the student, class subject, and selected semester
        $attitudeScores = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();

        // Calculate the average attitude score
        $averageAttitudeScore = $attitudeScores->avg('score');

        // Update or create a Grade model for attitude scores
        $attitudeGrade = Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterYearId
            ],
            ['average_attitude_score' => $averageAttitudeScore]
        );

        // Calculate the grade for attitude scores
        $attitudeGrade = $this->calculateGrade($averageAttitudeScore, 'score');

        // Retrieve skill scores for the student, class subject, and selected semester
        $skillScores = SkillScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();

        // Calculate the average skill score
        $averageSkillScore = $skillScores->avg('score');

        // Update or create a Grade model for skill scores
        $skillGrade = Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $selectedSemesterYearId
            ],
            ['average_skill_score' => $averageSkillScore]
        );

        // Calculate the grade for skill scores
        $skillGrade = $this->calculateGrade($averageSkillScore, 'score');

        // Return the view with the necessary data
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


    public function showDetailKnowledgeScore($studentId, $classSubjectId, Request $request)
    {
        // Ambil data siswa dan mata pelajaran
        $student = Student::findOrFail($studentId);
        $classSubject = ClassSubject::findOrFail($classSubjectId);

        // Ambil semua semester
        $semesters = SemesterYear::all();

        // Ambil ID semester yang dipilih atau setel ke semester pertama jika tidak ada yang dipilih
        $selectedSemesterYearId = $request->input('semester', SemesterYear::where('semester', 1)->first()->id);
        $semesterYear = SemesterYear::findOrFail($selectedSemesterYearId);

        // Ambil tipe penilaian yang berbeda
        $assessmentTypes = KnowledgeScore::distinct()->pluck('assessment_type');

        // Ambil nilai pengetahuan untuk siswa, mata pelajaran, dan semester tertentu
        $knowledgeScores = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();

        // Kirim data yang diperlukan ke tampilan
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
        // Lakukan validasi
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

        // Redirect ke halaman detailKnowledgeScore dengan pesan sukses
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

        // Retrieve Grade data
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
    // Lakukan validasi
    $request->validate([
        'score' => 'required|numeric|min:0|max:100',
        'description' => 'nullable|string',
        'semesterYearId' => 'required|exists:semester_years,id'
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

    // Jika attitudeScore belum ada di database, maka ini adalah input pertama
    if (!$attitudeScore->exists) {
        // Ambil final_score dari penilaian sebelumnya (jika ada)
        $previousAttitudeScore = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->orderBy('id', 'desc')
            ->first();

        $previousFinalScore = $previousAttitudeScore ? $previousAttitudeScore->final_score : 0;

        // Set nilai final_score
        $attitudeScore->score = $score;
        $attitudeScore->final_score = $previousFinalScore + $score;
        $attitudeScore->description = $request->input('description');
        $attitudeScore->save();
    } else {
        // Jika attitudeScore sudah ada, maka ini adalah update
        // Ambil nilai akhir sebelumnya
        $previousFinalScore = $attitudeScore->final_score;

        // Hitung selisih antara nilai baru dan nilai lama
        $scoreDifference = $score - $attitudeScore->score;

        // Perbarui nilai akhir untuk attitudeScore yang diubah
        $attitudeScore->score = $score;
        $attitudeScore->final_score = max(0, $attitudeScore->final_score + $scoreDifference);
        $attitudeScore->description = $request->input('description');
        $attitudeScore->save();

        // Perbarui nilai akhir untuk semua nilai yang berada di bawah attitudeScore yang diubah
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
    // Perbarui tabel grade
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

public function showDetailSkillScore($studentId, $classSubjectId, Request $request)
{
    // Ambil data siswa dan mata pelajaran
    $student = Student::findOrFail($studentId);
    $classSubject = ClassSubject::findOrFail($classSubjectId);

    // Ambil semua semester
    $semesters = SemesterYear::all();

    // Ambil ID semester yang dipilih atau setel ke semester pertama jika tidak ada yang dipilih
    $selectedSemesterYearId = $request->input('semester', SemesterYear::where('semester', 1)->first()->id);
    $semesterYear = SemesterYear::findOrFail($selectedSemesterYearId);

    // Ambil tipe penilaian yang berbeda
    $assessmentTypes = SkillScore::distinct()->pluck('assessment_type');

    // Ambil nilai pengetahuan untuk siswa, mata pelajaran, dan semester tertentu
    $skillScores = SkillScore::where('student_id', $studentId)
    ->where('class_subject_id', $classSubjectId)
    ->where('semester_year_id', $selectedSemesterYearId)
    ->get();

return view('grade.detailSkillScore', compact(
    'student',
    'classSubject',
    'semesterYear',
    'skillScores', // Corrected variable name
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

        // Assuming you have a method to fetch skill score data
        $skillScore = SkillScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYear->id)
            ->where('assessment_type', $assessmentType)
            ->first();

        // Assuming you have a view named 'editSkillScore'
        return view('grade.editSkillScore', compact('student', 'classSubject', 'semesterYear', 'skillScore', 'assessmentType'));
    }


    public function updateSkillScore(Request $request, $studentId, $classSubjectId, $semesterYearId, $assessmentType)
    {
        // Lakukan validasi
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        // Mendapatkan nilai yang diinput
        $score = $request->input('score');

        // Cari atau buat data nilai dari database
        $skillScore = SkillScore::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $semesterYearId,
                'assessment_type' => $assessmentType
            ],
            [
                'score' => $score,
                'description' => $request->input('description')
            ]
        );

        if (!$skillScore->exists) {
            // Ambil final_score dari penilaian sebelumnya (jika ada)
            $previousskillScore = AttitudeScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->orderBy('id', 'desc')
                ->first();

            $previousskillScore = $previousskillScore ? $previousskillScore->final_score : 0;

            // Set nilai final_score
            $skillScore->score = $score;
            $skillScore->final_score = $previousskillScore + $score;
            $skillScore->description = $request->input('description');
            $skillScore->save();
        } else {
            // Jika attitudeScore sudah ada, maka ini adalah update
            // Ambil nilai akhir sebelumnya
            $previousFinalScore = $skillScore->final_score;

            // Hitung selisih antara nilai baru dan nilai lama
            $scoreDifference = $score - $skillScore->score;

            // Perbarui nilai akhir untuk attitudeScore yang diubah
            $skillScore->score = $score;
            $skillScore->final_score = max(0, $skillScore->final_score + $scoreDifference);
            $skillScore->description = $request->input('description');
            $skillScore->save();

            // Perbarui nilai akhir untuk semua nilai yang berada di bawah attitudeScore yang diubah
            $skillScoresBelow = SkillScore::where('student_id', $studentId)
                ->where('class_subject_id', $classSubjectId)
                ->where('semester_year_id', $semesterYearId)
                ->where('id', '>', $skillScore->id)
                ->orderBy('id')
                ->get();

            foreach ($skillScoresBelow as $attitude) {
                $previousFinalScore += $attitude->score + $scoreDifference;
                $attitude->final_score = max(0, $previousFinalScore);
                $attitude->grade = $this->calculateGrade($attitude->final_score, 'final_score');
                $attitude->save();
            }

            $averageskillScore = SkillScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->avg('final_score');


            // Perbarui atau buat data di tabel Grade
            $grade = Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_subject_id' => $classSubjectId,
                    'semester_year_id' => $semesterYearId,
                ],
                [
                    'final_score' => $averageskillScore,
                    'grade' => $this->calculateGrade($averageskillScore, 'score')
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


        // Redirect ke halaman detailSkillScore dengan pesan sukses
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
}

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
