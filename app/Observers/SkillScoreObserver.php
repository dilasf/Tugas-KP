<?php

namespace App\Observers;

use App\Models\Grade;
use App\Models\SkillScore;

class SkillScoreObserver
{
    public function saved(SkillScore $skillScore)
    {
        $this->updateGrades($skillScore->student_id, $skillScore->class_subject_id, $skillScore->semester_year_id);
    }

    private function updateGrades($studentId, $classSubjectId, $semesterYearId)
    {
        $skillScores = SkillScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->get();

        $averageSkillScore = $skillScores->avg('score');
        $skillScoreId = $skillScores->last()->id ?? null;

        // Tambahkan pengecekan apakah nilai SkillScore sudah ada
        if ($skillScores->isNotEmpty()) {
            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_subject_id' => $classSubjectId,
                    'semester_year_id' => $semesterYearId,
                ],
                [
                    'skill_score_id' => $skillScoreId,
                    'average_skill_score' => $averageSkillScore,
                ]
            );
        }
    }
}
