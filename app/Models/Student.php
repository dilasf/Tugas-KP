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
        // 'height_weight_id',
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

    public function heightWeights()
    {
        return $this->hasMany(HeightWeight::class);
    }

    public function latestHeightWeight()
    {
        return $this->hasOne(HeightWeight::class)->latestOfMany();
    }
     // Mencari Method terbaru tak null bagian tinggi
     public function latestNonNullHeight()
     {
         return $this->hasMany(HeightWeight::class)
                     ->whereNotNull('height')
                     ->latest()
                     ->first();
     }

     // Mencari Method terbaru tak null bagian berat
     public function latestNonNullWeight()
     {
         return $this->hasMany(HeightWeight::class)
                     ->whereNotNull('weight')
                     ->latest()
                     ->first();
     }

     // Mencari Method terbaru tak null bagian kepala
     public function latestNonNullHeadSize()
     {
         return $this->hasMany(HeightWeight::class)
                     ->whereNotNull('head_size')
                     ->latest()
                     ->first();
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

    public function health()
    {
        return $this->hasOne(Health::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function extracurriculars()
    {
        return $this->hasMany(Extracurricular::class);
    }

    public function rapor()
{
    return $this->hasMany(Rapor::class);
}

}
