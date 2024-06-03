<?php

namespace App\Http\Controllers;

use App\Models\SemesterYear;
use App\Models\Subject;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $subjectCount = Subject::count();
        $studentCount = Student::count();
        $classCount = StudentClass::count();
        $teacherCount = Teacher::count();
        $semesterYearCount = SemesterYear::count();

        return view('dashboard', compact('subjectCount', 'studentCount', 'classCount', 'teacherCount', 'semesterYearCount'));
    }
}
