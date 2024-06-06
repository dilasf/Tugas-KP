<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\StudentClass;
use App\Models\Subject;

class ClassSubjectController extends Controller
{
    public function index()
    {
        // Sinkronkan semua kelas dengan semua mata pelajaran
        $this->syncClassSubjects();

        $classSubjects = ClassSubject::with(['class', 'subject'])
                                     ->join('classes', 'class_subjects.class_id', '=', 'classes.id')
                                     ->orderBy('classes.class_name')
                                     ->select('class_subjects.*')
                                     ->get();

        $sidebarOpen = false;
        return view('class-subjects.index', compact('classSubjects', 'sidebarOpen'));

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
