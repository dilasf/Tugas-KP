<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'activity',
        'description',
    ];

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }
}
