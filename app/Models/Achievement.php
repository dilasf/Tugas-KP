<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'achievement_type',
        'description',
    ];

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }
}
