<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterYear extends Model
{
    use HasFactory;

    protected $table = 'semester_years';

    protected $fillable = [
        'semester',
        'year',
    ];
}
