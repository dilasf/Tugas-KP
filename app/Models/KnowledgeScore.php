<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeScore extends Model
{
    use HasFactory;

    protected $table = 'knowledge_scores';

    protected $fillable = [
        'assessment_type',
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

    public function rapor()
    {
        return $this->hasMany(Rapor::class, 'student_id', 'student_id')->where('semester_year_id', $this->semester_year_id);
    }

}
