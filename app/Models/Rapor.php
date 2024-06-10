<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'grade_id',
        'school_name',
        'school_address',
        'suggestion',
        'health_id',
        'activity_id',
        'extracurricular_id',
        'print_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function health()
    {
        return $this->belongsTo(Health::class);
    }

    public function activity()
    {
        return $this->belongsTo(Achievement::class);
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function classSubject()
{
    return $this->belongsTo(ClassSubject::class, 'class_subject_id');
}

public function knowledgeScore()
{
    return $this->belongsTo(KnowledgeScore::class, 'knowledge_score_id');
}

public function attitudeScore()
{
    return $this->belongsTo(AttitudeScore::class, 'attitude_score_id');
}

public function skillScore()
{
    return $this->belongsTo(SkillScore::class, 'skill_score_id');
}

public function semesterYear()
{
    return $this->belongsTo(SemesterYear::class);
}


}
