<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\Rapor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExtracurricularController extends Controller
{
    public function create($studentId, $semester_year_id)
    {
        $student = Student::findOrFail($studentId);

        $rapors = Rapor::whereHas('grade', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
        ->where('semester_year_id', $semester_year_id)
        ->get();

        return view('extracurricular.create', [
            'student' => $student,
            'rapors' => $rapors,
            'semester_year_id' => $semester_year_id,
        ]);
    }

    public function store(Request $request, $studentId, $semester_year_id)
    {
        $validated = $request->validate([
            'activity' => [
                'required',
                'max:200',
                Rule::unique('extracurriculars')->where(function ($query) use ($studentId, $semester_year_id) {
                    $query->whereIn('rapor_id', function ($query) use ($studentId, $semester_year_id) {
                        $query->select('id')
                            ->from('rapors')
                            ->whereHas('grade', function ($query) use ($studentId) {
                                $query->where('student_id', $studentId);
                            })
                            ->where('semester_year_id', $semester_year_id);
                    });
                }),
            ],
            'description' => 'nullable|max:255',
        ]);

        try {
            $rapor = Rapor::whereHas('grade', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->where('semester_year_id', $semester_year_id)
            ->firstOrFail();

            $validated['rapor_id'] = $rapor->id;

            Extracurricular::create($validated);

            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Ekstrakurikuler Berhasil Disimpan';
            return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);

        } catch (\Exception $e) {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Gagal menyimpan data Ekstrakurikuler: ' . $e->getMessage();
            return redirect()->route('extracurriculars.create', ['studentId' => $studentId, 'semester_year_id' => $semester_year_id])->withInput()->with($notification);
        }
    }

    public function edit($studentId, $extracurricularId)
    {
        $student = Student::findOrFail($studentId);
        $extracurricular = Extracurricular::findOrFail($extracurricularId);

        $semester_year_id = $extracurricular->rapor->grade->semester_year_id;

        $rapors = Rapor::whereHas('grade', function ($query) use ($studentId, $semester_year_id) {
            $query->where('student_id', $studentId)
                  ->where('semester_year_id', $semester_year_id);
        })->get();

        return view('extracurricular.edit', [
            'student' => $student,
            'extracurricular' => $extracurricular,
            'rapors' => $rapors,
            'semester_year_id' => $semester_year_id,
        ]);
    }

    public function update(Request $request, $studentId, $extracurricularId)
    {
        $validated = $request->validate([
            'activity' => 'required|max:200',
            'description' => 'nullable|max:255',
        ]);

        $extracurricular = Extracurricular::find($extracurricularId);

        if (!$extracurricular) {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Ekstrakurikuler tidak ditemukan';
            return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);
        }

        // Update field activity dan description
        $extracurricular->activity = $validated['activity'];
        $extracurricular->description = $validated['description'];
        $extracurricular->save();

        $notification['alert-type'] = 'success';
        $notification['message'] = 'Data Ekstrakurikuler Berhasil Diperbaharui';
        return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);
    }

    public function destroy($extracurricularId)
    {
        $extracurricular = Extracurricular::findOrFail($extracurricularId);
        $studentId = $extracurricular->rapor->grade->student_id;

        if ($extracurricular->delete()) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Ekstrakurikuler Berhasil Dihapus';
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Ekstrakurikuler Gagal Dihapus';
        }

        return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);
    }


}

