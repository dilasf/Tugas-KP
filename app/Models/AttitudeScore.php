<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttitudeScore extends Model
{
    use HasFactory;
    protected $table = 'attitude_scores';

    protected $fillable = [
        'assessment_type',
        'score',
        'final_score',
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
