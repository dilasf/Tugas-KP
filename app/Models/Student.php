<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Student extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'nis',
//         'nisn',
//         'nipd',
//         'class_id',
//         'student_name',
//         'gender',
//         'nik',
//         'place_of_birth',
//         'date_of_birth',
//         'religion',
//         'address',
//         'special_needs',
//         'previous_school',
//         'birth_certificate_number',
//         'residence_type',
//         'guardian_id',
//         // 'height_weight_id',
//         'no_kk',
//         'child_number',
//         'number_of_siblings',
//         'transportation',
//         'distance_to_school',
//         'student_photo',
//         'status',
//     ];

//     public function guardian()
//     {
//         return $this->belongsTo(Guardian::class, 'guardian_id');
//     }

//     public function heightWeights()
//     {
//         return $this->hasMany(HeightWeight::class);
//     }

//     public function latestHeightWeight()
//     {
//         return $this->hasOne(HeightWeight::class)->latestOfMany();
//     }

//     public function class()
//     {
//         return $this->belongsTo(StudentClass::class, 'class_id', 'id');
//     }

//     public function grades()
//     {
//         return $this->hasMany(Grade::class, 'student_id', 'id');
//     }

//     public function classSubjects()
//     {
//         return $this->hasMany(ClassSubject::class);
//     }

//     public function health()
//     {
//         return $this->hasOne(Health::class);
//     }

//     public function achievements()
//     {
//         return $this->hasMany(Achievement::class);
//     }

//     public function extracurriculars()
//     {
//         return $this->hasMany(Extracurricular::class);
//     }

//     public function rapors()
//     {
//         return $this->hasMany(Rapor::class);
//     }

//     public function user()
//     {
//         return $this->hasOne(User::class);
//     }

//     protected static function boot()
//     {
//         parent::boot();

//         static::deleting(function ($student) {
//             $student->heightWeights()->delete();
//             $student->grades()->delete();
//             $student->classSubjects()->delete();
//             $student->health()->delete();
//             $student->achievements()->delete();
//             $student->extracurriculars()->delete();
//             $student->rapors()->delete();
//             if ($student->user) {
//                 $student->user->delete();
//             }
//             if ($student->guardian) {
//                 $student->guardian->delete();
//             }
//         });
//     }
// }


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis', 'nisn', 'nipd', 'class_id', 'student_name', 'gender', 'nik',
        'place_of_birth', 'date_of_birth', 'religion', 'address', 'special_needs',
        'previous_school', 'birth_certificate_number', 'residence_type', 'guardian_id',
        'no_kk', 'child_number', 'number_of_siblings', 'transportation',
        'distance_to_school', 'student_photo', 'status',
    ];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }

    public function heightWeights()
    {
        return $this->hasMany(HeightWeight::class, 'student_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id', 'id');
    }

    public function latestHeightWeight()
    {
        return $this->hasOne(HeightWeight::class)->latestOfMany();
    }

    public function latestNonNullHeight()
    {
        return $this->heightWeights()->whereNotNull('height')->latest()->first();
    }

    public function latestNonNullWeight()
    {
        return $this->heightWeights()->whereNotNull('weight')->latest()->first();
    }

    public function latestNonNullHeadSize()
    {
        return $this->heightWeights()->whereNotNull('head_size')->latest()->first();
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'student_id', 'id');
    }

    public function health()
    {
        return $this->hasOne(Health::class, 'student_id', 'id');
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class, 'student_id', 'id');
    }

    public function extracurriculars()
    {
        return $this->hasMany(Extracurricular::class, 'student_id', 'id');
    }

    public function rapors()
    {
        return $this->hasMany(Rapor::class, 'student_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'student_id', 'id');
    }


}
