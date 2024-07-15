<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Rapor;
use App\Models\SkillScore;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function incrementSick(Request $request)
    {
        // $studentId = $request->input('student_id');
        $this->updateAttendance($request, 'sick', true);
        return redirect()->back();
    }

    public function incrementPermission(Request $request)
    {
        $this->updateAttendance($request, 'permission', true);
        return redirect()->back();
    }

    public function incrementUnexcused(Request $request)
    {
        $this->updateAttendance($request, 'unexcused', true);
        return redirect()->back();
    }

    public function decrementSick(Request $request)
    {
        $this->updateAttendance($request, 'sick', false);
        return redirect()->back();
    }

    public function decrementPermission(Request $request)
    {
        $this->updateAttendance($request, 'permission', false);
        return redirect()->back();
    }

    public function decrementUnexcused(Request $request)
    {
        $this->updateAttendance($request, 'unexcused', false);
        return redirect()->back();
    }

    private function updateAttendance(Request $request, $type, $increment)
{
    $studentId = $request->input('student_id');
    $classSubjectId = $request->input('class_subject_id');
    $semesterYearId = $request->input('semester_year_id');

    // Find or create attendance record for today
    $attendance = Attendance::firstOrNew([
        'student_id' => $studentId,
        'class_subject_id' => $classSubjectId,
        'semester_year_id' => $semesterYearId,
    ]);

    // Set default values if creating new record
    if (!$attendance->exists) {
        $attendance->sick = 0;
        $attendance->permission = 0;
        $attendance->unexcused = 0;
    }

    // Update the specific attendance type
    if ($increment) {
        $attendance->$type++;
    } else {
        $attendance->$type--;
    }

    // Save the attendance record
    $attendance->save();

    // Update SkillScore to associate with this attendance
    $grade = Grade::where('student_id', $studentId)
        ->where('class_subject_id', $classSubjectId)
        ->where('semester_year_id', $semesterYearId)
        ->first();

    if ($grade) {
        // SkillScore::updateOrCreate(
        //     [
        //         'grade_id' => $grade->id,
        //         'teacher_id' => $grade->teacher_id,
        //     ],
        //     [
        //         'attendance_id' => $attendance->id,
        //     ]
        // );

        Rapor::updateOrCreate(
            [
                'grade_id' => $grade->id,
            ],
            [
                'attendance_id' => $attendance->id,
            ]
        );
    }
}


}
