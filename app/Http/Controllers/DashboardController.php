<?php

namespace App\Http\Controllers;

use App\Models\AttitudeScore;
use App\Models\KnowledgeScore;
use App\Models\SemesterYear;
use App\Models\SkillScore;
use App\Models\Subject;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Teacher;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $subjectCount = Subject::count();
        $studentCount = Student::count();
        $classCount = StudentClass::count();
        $teacherCount = Teacher::count();
        $semesterYearCount = SemesterYear::count();

        // Menghitung jumlah tipe penilaian unik dari KnowledgeScore
        $assessmentTypesKnowledge = KnowledgeScore::select('assessment_type')
                                                  ->distinct()
                                                  ->count();

        // Menghitung jumlah tipe penilaian unik dari AttitudeScore
        $assessmentTypesAttitude = AttitudeScore::select('assessment_type')
                                                ->distinct()
                                                ->count();

        // Menghitung jumlah tipe penilaian unik dari SkillScore
        $assessmentTypesSkill = SkillScore::select('assessment_type')
                                           ->distinct()
                                           ->count();

        return view('dashboard-admin', compact('subjectCount', 'studentCount', 'classCount', 'teacherCount', 'semesterYearCount', 'assessmentTypesKnowledge', 'assessmentTypesAttitude', 'assessmentTypesSkill'));
    }


}
