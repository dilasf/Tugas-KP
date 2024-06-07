<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function update(Request $request, $studentId, $classSubjectId, $semesterYearId, $type, $action)
    {
        $attendance = Attendance::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId
        ]);

        $attendance->$type = $attendance->$type ?? 0;

        if ($action == 'increment') {
            $attendance->$type += 1;
        } elseif ($action == 'decrement' && $attendance->$type > 0) {
            $attendance->$type -= 1;
        }

        $attendance->save();

        return redirect()->back()->with('success', 'Attendance updated successfully.');
    }
}
