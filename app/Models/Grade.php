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
        'teacher_id',
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
                $rapor = Rapor::updateOrCreate(
                    [
                        'grade_id' => $grade->id,
                    ],
                    [
                        'school_name' => 'SDN DAWUAN',
                        'school_address' => 'KP Pasir Eurih',
                    ]
                );

                // Update atau buat HeightWeight
                HeightWeight::updateOrCreate(
                    [
                        'rapor_id' => $rapor->id,
                        'student_id' => $grade->student_id,
                    ],
                    [
                        // tambahkan field lain jika diperlukan
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

    public function teacher()
    {
        return $this->morphTo();
    }

    public function skillScores()
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
