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
        // 'knowledge_score_id',
        // 'attitude_score_id',
        // 'skill_score_id',
        'average_knowledge_score',
        'average_attitude_score',
        'average_skill_score',
        'gradeKnowledges',
        'gradeAttitude',
        'gradeSkill',
        'descriptionKnowledge',
        'descriptionAttitude',
        'descriptionSkill',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($grade) {
            // Pastikan nilai semester_year_id ada sebelum disimpan ke dalam Rapor
            if ($grade->semester_year_id) {
                // Cari atau buat instance Rapor
                Rapor::updateOrCreate(
                    [
                        // 'student_id' => $grade->student_id,
                        'grade_id' => $grade->id,
                        // 'class_subject_id' => $grade->class_subject_id,
                    ],
                    [
                        'school_name' => 'SDN DAWUAN',
                        'school_address' => 'KP Pasir Eurih',
                        // 'semester_year_id' => $grade->semester_year_id,
                    ]
                );
            }
        });
    }



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

    public function knowledgeScores()
    {
        return $this->hasMany(KnowledgeScore::class);
    }

    public function attitudeScore()
    {
        return $this->hasMany(AttitudeScore::class);
    }

    public function skillScore()
    {
        return $this->hasMany(SkillScore::class);
    }

    // public function rapor()
    // {
    //     return $this->hasMany(Rapor::class);
    // }

    public function rapor()
    {
        return $this->hasOne(Rapor::class, 'grade_id');
    }
}
