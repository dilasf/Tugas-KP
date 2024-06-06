<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nisn',
        'nipd',
        'class_id',
        'student_name',
        'gender',
        'nik',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'address',
        'special_needs',
        'previous_school',
        'birth_certificate_number',
        'residence_type',
        'guardian_id',
        'height_weight_id',
        'no_kk',
        'child_number',
        'number_of_siblings',
        'transportation',
        'distance_to_school',
        'student_photo',
        'status',
    ];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }

    public function heightWeight()
    {
        return $this->belongsTo(HeightWeight::class, 'height_weight_id');
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id', 'id');
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }

}
