<?php

namespace App\Http\Controllers;

use App\Models\AttitudeScore;
use App\Models\KnowledgeScore;
use App\Models\Rapor;
use App\Models\SemesterYear;
use App\Models\SkillScore;
use App\Models\Subject;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $subjectCount = Subject::count();
        $studentCount = Student::count();
        $classCount = StudentClass::count();
        $teacherCount = Teacher::count();
        $semesterYearCount = SemesterYear::count();
        $accountsCount = User::count();
        $pendingReportsCount = Rapor::where('status', 'waiting_validation')->count();

        $teacher = Auth::user();

        // Menghitung jumlah tipe penilaian unik dari KnowledgeScore
        $assessmentTypesKnowledge = KnowledgeScore::where('teacher_id', $teacher->teacher_id)
                                                    ->select('assessment_type')
                                                    ->distinct()
                                                    ->pluck('assessment_type');

        $assessmentTypesKnowledgeCollection = collect($assessmentTypesKnowledge);
        $uniqueKnowledge = $assessmentTypesKnowledgeCollection->count();

        // Menghitung jumlah tipe penilaian unik dari AttitudeScore
        $assessmentTypesAttitude = AttitudeScore::where('teacher_id', $teacher->teacher_id)
                                                ->select('assessment_type')
                                                ->distinct()
                                                ->pluck('assessment_type');

        $assessmentTypesAttitudeCollection = collect($assessmentTypesAttitude);
        $uniqueAttitude = $assessmentTypesAttitudeCollection->count();

        //menghitung jumlah tipe penilaian dari keterampilan
        $assessmentTypesSkill = SkillScore::where('teacher_id', $teacher->teacher_id)
                                   ->select('assessment_type')
                                   ->distinct()
                                   ->pluck('assessment_type');

        $assessmentTypesSkillCollection = collect($assessmentTypesSkill);
        $uniqueSkill = $assessmentTypesSkillCollection->count();

        $validatedReports = collect();

        if (Auth::user()->role_id == 5) { // Role ID 5 adalah siswa
            $studentId = Auth::user()->student_id;
            $validatedReports = Rapor::whereHas('grade', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })->where('status', 'validated')
            ->with(['grade.classSubject.class', 'grade.semesterYear'])
            ->get();
        }


        return view('dashboard-admin', compact('subjectCount', 'studentCount', 'classCount', 'teacherCount', 'accountsCount', 'semesterYearCount', 'uniqueSkill', 'uniqueAttitude', 'uniqueKnowledge','pendingReportsCount', 'validatedReports'));
    }


}
