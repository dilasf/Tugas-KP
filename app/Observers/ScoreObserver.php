<?php
namespace App\Observers;

use App\Models\KnowledgeScore;
use App\Models\Grade;

class ScoreObserver
{
    public function saved(KnowledgeScore $knowledgeScore)
    {
        $this->updateGrades($knowledgeScore->student_id, $knowledgeScore->class_subject_id, $knowledgeScore->semester_year_id);
    }

    private function updateGrades($studentId, $classSubjectId, $semesterYearId)
    {
        $knowledgeScores = KnowledgeScore::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->where('semester_year_id', $semesterYearId)
            ->get();
        $averageKnowledgeScore = $knowledgeScores->avg('score');
        $knowledgeScoreId = $knowledgeScores->last()->id ?? null;

        Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_subject_id' => $classSubjectId,
                'semester_year_id' => $semesterYearId,
            ],
            [
                'knowledge_score_id' => $knowledgeScoreId,
                'average_knowledge_score' => $averageKnowledgeScore,
            ]
        );
    }
}
