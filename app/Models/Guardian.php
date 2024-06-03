<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'father_name',
        'mother_name',
        'father_nik',
        'mother_nik',
        'father_birth_year',
        'mother_birth_year',
        'father_education',
        'mother_education',
        'father_occupation',
        'mother_occupation',
        'father_income',
        'mother_income',
        'parent_phone_number',
        'parent_email',
        'guardian_name',
        'guardian_nik',
        'guardian_birth_year',
        'guardian_education',
        'guardian_occupation',
        'guardian_income',
        'guardian_phone_number',
        'guardian_email'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'guardian_id');
    }

}
