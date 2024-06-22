<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeightWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'rapor_id',
        'semester_year_id',
        'height',
        'weight',
        'head_size'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }

    public function semesterYear()
    {
        return $this->belongsTo(SemesterYear::class);
    }
}
