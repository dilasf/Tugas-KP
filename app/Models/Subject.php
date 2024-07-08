<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_name',
        'kkm',
    ];

    public function classes()
    {
        return $this->belongsToMany(StudentClass::class, 'class_subjects', 'subject_id', 'class_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'teaching', 'subject_name');
    }

}
