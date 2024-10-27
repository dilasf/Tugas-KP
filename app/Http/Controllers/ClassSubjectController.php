<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class ClassSubjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $roleId = $user->role_id;

        $sidebarOpen = false;

        if ($roleId == 4) { // Guru Kelas
            $this->syncClassSubjects();
            $classes = StudentClass::where('homeroom_teacher_id', $teacher->id)->get();

            // Ambil mata pelajaran yang diajarkan di kelas-kelas tersebut
            // $classSubjects = ClassSubject::with(['class', 'subject'])
            //     ->whereIn('class_id', $classes->pluck('id'))
            //     ->orderBy('class_id')
            //     ->get()
            //     ->filter(function ($classSubject) {
            //         return $classSubject->subject->teachers->isEmpty();
            //     });

            $classSubjects = ClassSubject::with(['class', 'subject'])
            ->whereIn('class_id', $classes->pluck('id'))
            ->whereDoesntHave('subject.teachers', function ($query) {
                $query->where('typesOfCAR', 'guru mapel');
            })
            ->orderBy('class_id')
            ->get();

            return view('class-subjects.index-subject', compact('classSubjects', 'sidebarOpen', 'classes', 'teacher'));

        } elseif ($roleId == 3) { // Guru Mapel
            $teachingSubject = $teacher->teaching;

            $subject = Subject::where('subject_name', $teachingSubject)->first();

            if ($subject) {
                // Ambil semua class subject yang sesuai dengan subject yang diajarkan
                $classSubjects = ClassSubject::with(['class', 'subject'])
                    ->where('subject_id', $subject->id)
                    ->orderBy('class_id')
                    ->get();

                $classes = StudentClass::whereIn('id', $classSubjects->pluck('class_id'))
                    ->orderBy('class_name')
                    ->get();
            } else {
                $classSubjects = collect();
                $classes = collect();
            }

            return view('class-subjects.index-class', compact('classSubjects', 'sidebarOpen', 'teacher', 'classes'));
        } else {
            // Jika role_id tidak sesuai
            return abort(403, 'Unauthorized action.');
        }
    }


    public function show($id)
    {
        $classSubject = ClassSubject::with(['class', 'subject'])->findOrFail($id);
        $students = $classSubject->class->students;

        return view('class-subjects.show', compact('classSubject', 'students'));
    }

    private function syncClassSubjects()
    {
        $classes = StudentClass::all();
        $subjects = Subject::all();

        foreach ($classes as $class) {
            foreach ($subjects as $subject) {
                ClassSubject::firstOrCreate([
                    'class_id' => $class->id,
                    'subject_id' => $subject->id
                ]);
            }
        }
    }
}
