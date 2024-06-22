<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeightWeight;
use App\Models\Rapor;
use App\Models\SemesterYear;
use App\Models\Student;

class HeightWeightController extends Controller
{
    public function edit($studentId, $heightWeightId, $aspectName, Request $request)
    {
        $semester_year_id = $request->input('semester_year_id');
        $student = Student::findOrFail($studentId);

        $heightWeight = HeightWeight::with('rapor.grade')
            ->where('id', $heightWeightId)
            ->where('student_id', $studentId)
            ->first();

        // menemukan data sebelumnya untuk mengedit
        if (!$heightWeight || !$heightWeight->rapor || !$heightWeight->rapor->grade) {
            $previousSemesterYearId = SemesterYear::where('id', '<', $semester_year_id)
                ->orderByDesc('id')
                ->first();

            if ($previousSemesterYearId) {
                $heightWeight = HeightWeight::whereHas('rapor.grade', function ($query) use ($previousSemesterYearId) {
                    $query->where('semester_year_id', $previousSemesterYearId->id);
                })
                ->where('student_id', $studentId)
                ->first();
            }
        }

        return view('heightWeight.edit', [
            'student' => $student,
            'heightWeight' => $heightWeight,
            'semester_year_id' => $semester_year_id,
            'aspectName' => $aspectName,
        ]);
    }


    public function update(Request $request, $studentId, $heightWeightId, $aspectName)
    {
        $validated = $request->validate([
            'height' => 'nullable|integer',
            'weight' => 'nullable|integer',
            'head_size' => 'nullable|integer',
            'semester_year_id' => 'required|exists:semester_years,id',
        ]);

        $heightWeight = HeightWeight::whereHas('rapor.grade', function ($query) use ($validated) {
            $query->where('semester_year_id', $validated['semester_year_id']);
        })->where('student_id', $studentId)->first();

        if (!$heightWeight) {
            // Jika tidak ada, buat entri baru
            $heightWeight = new HeightWeight([
                'student_id' => $studentId,
                'rapor_id' => $heightWeightId,
            ]);
        }

        switch ($aspectName) {
            case 'Tinggi Badan':
                $heightWeight->height = $validated['height'];
                break;
            case 'Berat Badan':
                $heightWeight->weight = $validated['weight'];
                break;
            case 'Ukuran Kepala':
                $heightWeight->head_size = $validated['head_size'];
                break;
            default:
                return redirect()->route('height_weights.edit', [
                    'studentId' => $studentId,
                    'heightWeightId' => $heightWeightId,
                    'aspectName' => $aspectName,
                ])->withErrors(['error' => 'Invalid aspectName.']);
        }

        $heightWeight->save();

        return redirect()->route('rapors.index', ['studentId' => $studentId, 'semester_year_id' => $validated['semester_year_id']])
            ->with('success', 'Data tinggi berat badan berhasil diperbarui');
    }
}
