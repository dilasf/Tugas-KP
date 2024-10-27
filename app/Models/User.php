<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teacher_id',
        'student_id',
        'role_id',
        'name',
        'email',
        'password',
        'nuptk',
        'nip',
        'nis',
        'nisn',
        'status',
        'remember_token'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function hasValidTeacherId()
    {
        return $this->teacher_id !== null;
    }


    public function isActiveTeacher()
    {
        return $this->teacher && $this->teacher->status === 'active';
    }

    public function notActiveTeacher()
    {
        return $this->teacher && $this->teacher->status === 'inactive';
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($user) {
    //         // Validate uniqueness of NUPTK for users with admin role
    //         if ($user->hasRole('admin') && $user->nuptk) {
    //             $existingUser = User::where('nuptk', $user->nuptk)->first();
    //             if ($existingUser) {
    //                 throw new \Exception('NUPTK must be unique.');
    //             }
    //         }

    //         // Validate uniqueness of NIP if provided
    //         if ($user->nip) {
    //             $existingUser = User::where('nip', $user->nip)->first();
    //             if ($existingUser) {
    //                 throw new \Exception('NIP must be unique.');
    //             }
    //         }
    //     });
    // }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
