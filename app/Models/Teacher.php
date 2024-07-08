<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'nuptk',
        'teacher_name',
        'placeOfbirth',
        'dateOfbirth',
        'gender',
        'religion',
        'address',
        'mail',
        'mobile_phone',
        'nip',
        'employment_status',
        'typesOfCAR',
        'prefix',
        'suffix',
        'education_Level',
        'fieldOfStudy',
        'certification',
        'startDateofEmployment',
        'additional_Duties',
        'teaching',
        'competency',
        'status',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function classes()
    {
        return $this->hasMany(StudentClass::class, 'homeroom_teacher_id');
    }

    public function subjects()
    {
        return Subject::whereIn('subject_name', explode(',', $this->teaching))->get();
    }

    public function classSubjects()
    {
        return $this->belongsToMany(ClassSubject::class, 'teacher_class_subject');
    }

}
