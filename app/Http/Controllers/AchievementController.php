<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Rapor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AchievementController extends Controller
{
    public function create($studentId, $semester_year_id)
    {
        $student = Student::findOrFail($studentId);

        $rapors = Rapor::whereHas('grade', function ($query) use ($studentId, $semester_year_id) {
            $query->where('student_id', $studentId)
                ->where('semester_year_id', $semester_year_id);
        })->get();

        return view('achievement.create', [
            'student' => $student,
            'rapors' => $rapors,
            'semester_year_id' => $semester_year_id,
        ]);
    }

    public function store(Request $request, $studentId, $semester_year_id)
    {
        $validated = $request->validate([
            'achievement_type' => 'required|max:200',
            'description' => 'nullable|max:255',
        ]);

        try {
            // Cari rapor yang sesuai dengan studentId dan semester_year_id
            $rapor = Rapor::whereHas('grade', function ($query) use ($studentId, $semester_year_id) {
                $query->where('student_id', $studentId)
                    ->where('semester_year_id', $semester_year_id);
            })->firstOrFail();

            $achievementData = [
                'rapor_id' => $rapor->id,
                'achievement_type' => $validated['achievement_type'],
                'description' => $validated['description'],
            ];

            Achievement::create($achievementData);

            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Prestasi Berhasil Disimpan';
            return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);

        } catch (\Exception $e) {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Gagal menyimpan data Prestasi: ' . $e->getMessage();
            return redirect()->route('achievements.create', ['studentId' => $studentId, 'semester_year_id' => $semester_year_id])->withInput()->with($notification);
        }
    }

    public function edit($studentId, $extracurricularId)
    {
        $student = Student::findOrFail($studentId);
        $achievement = Achievement::findOrFail($extracurricularId);

        $semester_year_id = $achievement->rapor->grade->semester_year_id;

        $rapors = Rapor::whereHas('grade', function ($query) use ($studentId, $semester_year_id) {
            $query->where('student_id', $studentId)
                  ->where('semester_year_id', $semester_year_id);
        })->get();

        return view('achievement.edit', [
            'student' => $student,
            'achievement' => $achievement,
            'rapors' => $rapors,
            'semester_year_id' => $semester_year_id,
        ]);
    }

    public function update(Request $request, $studentId, $achievementId)
    {
        $validated = $request->validate([
            'achievement_type' => 'required|max:200',
            'description' => 'nullable|max:255',
        ]);

        $achievement = Achievement::find($achievementId);

        if (!$achievement) {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Prestasi tidak ditemukan';
            return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);
        }

        // Update field achievement_type dan description
        $achievement->achievement_type = $validated['achievement_type'];
        $achievement->description = $validated['description'];
        $achievement->save();

        $notification['alert-type'] = 'success';
        $notification['message'] = 'Data Prestasi Berhasil Diperbaharui';
        return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);
    }

    public function destroy($achievementId)
    {
        $achievement = Achievement::findOrFail($achievementId);
        $rapor = $achievement->rapor;

        if ($achievement->delete()) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Prestasi Berhasil Dihapus';
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Prestasi Gagal Dihapus';
        }

        return redirect()->route('rapors.index', ['studentId' => $rapor->grade->student_id])->with($notification);
    }
}
