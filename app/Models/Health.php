<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Health extends Model
{
    use HasFactory;

    protected $fillable = [
        'rapor_id',
        'physical_aspect',
        'description',
    ];

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }
}
