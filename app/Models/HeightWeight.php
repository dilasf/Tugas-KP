<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeightWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'height',
        'weight',
        'head_size',
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'height_weight_id');
    }
}
