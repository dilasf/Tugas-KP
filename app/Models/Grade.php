<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_subject_id',
        'semester_year_id',
        'knowledge_score_id',
        'attitude_score_id',
        'skill_score_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }

    public function semesterYear()
    {
        return $this->belongsTo(SemesterYear::class);
    }

    public function knowledgeScore()
    {
        return $this->belongsTo(KnowledgeScore::class);
    }

    public function attitudeScore()
    {
        return $this->belongsTo(AttitudeScore::class);
    }

    public function skillScore()
    {
        return $this->belongsTo(SkillScore::class);
    }
}
