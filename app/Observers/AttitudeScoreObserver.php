<?php

namespace App\Observers;

use App\Models\AttitudeScore;
use App\Models\Grade;
use App\Models\SemesterYear;

class AttitudeScoreObserver
{
    public function saved(AttitudeScore $attitudeScore)
    {
        $this->updateGrades($attitudeScore->student_id, $attitudeScore->class_subject_id, $attitudeScore->semester_year_id);
    }

    private function updateGrades($studentId, $classSubjectId, $semesterYearId)
    {
        $attitudeScores = AttitudeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->get();

        $averageAttitudeScore = $attitudeScores->avg('score');
        $attitudeScoreId = $attitudeScores->last()->id ?? null;

        Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $semesterYearId,
            ],
            [
                'attitude_score_id' => $attitudeScoreId,
                'average_attitude_score' => $averageAttitudeScore,
            ]
        );
    }
}
