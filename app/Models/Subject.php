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

    public static function getDataTeachers()
    {
        $subjects = Subject::all();
        $subjects_filter = [];

        $no = 1;
        foreach ($subjects as $mapel) {
            $subjects_filter[] = [
                'no' => $no++,
                'subject_name' => $mapel->subject_name,
                'kkm' => $mapel->kkm,
            ];
        }

        return $subjects_filter;
    }
}
