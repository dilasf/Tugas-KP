<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillScore extends Model
{
    use HasFactory;

    protected $table = 'skill_scores';

    protected $fillable = [
        'grade_id',
        'assessment_type',
        'teacher_id',
        'attendance_id',
        'score',
        'final_score',
        'grade',
        'description',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function attendance()
{
    return $this->belongsTo(Attendance::class);
}

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }
}
