<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'class_subject_id',
        'semester_year_id',
        // 'rapor_id',
        'sick',
        'permission',
        'unexcused',
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

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }

    public function skillScores()
    {
        return $this->hasMany(SkillScore::class);
    }
}
