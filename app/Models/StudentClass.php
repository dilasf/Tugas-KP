<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentClass extends Model
{
    use HasFactory;
    protected $table = 'classes';


    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id', 'id');
    }

    protected $fillable = [
        'class_name',
        'level',
        'number_of_male_students',
        'number_of_female_students',
        'number_of_students',
        'homeroom_teacher_id',
        'curriculum',
        'room'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
