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

    // public static function getDataTeachers()
    // {
    //     $teachers = Teacher::all();
    //     $teachers_filter = [];

    //     $no = 1;
    //     foreach ($teachers as $teacher) {
    //         $teachers_filter[] = [
    //             'no' => $no++,
    //             'photo' => $teacher->photo,
    //             'nuptk' => $teacher->nuptk,
    //             'teacher_name' => $teacher->teacher_name,
    //             'placeOfbirth' => $teacher->placeOfbirth,
    //             'dateOfbirth' => $teacher->dateOfbirth,
    //             'gender' => $teacher->gender,
    //             'religion' => $teacher->religion,
    //             'address' => $teacher->address,
    //             'mail' => $teacher->mail,
    //             'mobile_phone' => $teacher->mobile_phone,
    //             'nip'=> $teacher->nip,
    //             'employment_status'=> $teacher->employment_status,
    //             'typesOfCAR'=> $teacher->typesOfCAR,
    //             'prefix'=> $teacher->prefix,
    //             'suffix'=> $teacher->suffix,
    //             'education_Level'=> $teacher->education_Level,
    //             'fieldOfStudy'=> $teacher->fieldOfStudy,
    //             'certification'=> $teacher->certification,
    //             'startDateofEmployment'=> $teacher->startDateofEmployment,
    //             'additional_Duties'=> $teacher->additional_Duties,
    //             'teaching'=> $teacher->teaching,
    //             'competency'=> $teacher->competency,
    //             'status' => $teacher->status,
    //         ];
    //     }

    //     return $teachers_filter;
    // }
}
