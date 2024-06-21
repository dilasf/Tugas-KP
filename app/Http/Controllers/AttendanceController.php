<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Rapor;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function update(Request $request, $studentId, $classSubjectId, $semesterYearId, $type, $action)
    {
        // menemukan track sesuai
        $grade = Grade::where('student_id', $studentId)
            ->where('semester_year_id', $semesterYearId)
            ->first();

        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'Grade not found'
            ]);
        }

        $rapor = Rapor::firstOrCreate([
            'grade_id' => $grade->id,
        ], [
            'school_name' => 'SDN DAWUAN',
            'school_address' => 'KP Pasir Eurih',
            'print_date' => now()
        ]);

        $date = now()->toDateString();

        $attendance = Attendance::firstOrNew([
            'student_id' => $studentId,
            'class_subject_id' => $classSubjectId,
            'semester_year_id' => $semesterYearId,
            'date' => $date,
        ]);

        // menentukan rapor id
        $attendance->rapor_id = $rapor->id;

        // inisialisasi type (sakit, alpha, izin)
        $attendance->$type = $attendance->$type ?? 0;

        //tambah atau kurang nilai berdasarkan aksi
        if ($action == 'increment') {
            $attendance->$type += 1;
        } elseif ($action == 'decrement' && $attendance->$type > 0) {
            $attendance->$type -= 1;
        }

        $attendance->save();

        return response()->json([
            'success' => true,
            'new_value' => $attendance->$type
        ]);
    }

    }

