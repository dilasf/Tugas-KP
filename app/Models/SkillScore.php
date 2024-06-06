<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillScore extends Model
{
    use HasFactory;

    protected $table = 'skill_scores';

    protected $fillable = [
        'assessment_type',
        'attendance_id',
        'score',
        'final_score',
        'grade',
        'description',
        'student_id',
        'class_subject_id',
        'semester_year_id',
    ];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
