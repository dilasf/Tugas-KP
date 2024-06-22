<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\Grade;
use App\Models\HeightWeight;
use App\Models\Rapor;
use App\Models\SemesterYear;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    public function index(Request $request, $studentId)
    {
        $selectedSemesterYearId = $request->input('semester_year_id');
        $semesters = SemesterYear::all();
        $sidebarOpen = false;

        $currentMonth = now()->month;
        $defaultSemester = $semesters->firstWhere('semester', ($currentMonth >= 1 && $currentMonth <= 6) ? 1 : 2);

        if (!$selectedSemesterYearId) {
            $selectedSemesterYearId = $defaultSemester->id;
        }

        // menemukan data guru
        $student = Student::with(['class.teacher', 'heightWeights'])->findOrFail($studentId);
        $headmaster = Teacher::where('typesOfCAR', 'Kepala Sekolah')->first();

        // meneukan data grade sesuai semester
        $grades = Grade::where('student_id', $studentId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();

        // sambungkan dengan tabel rapor
        $rapors = Rapor::whereIn('grade_id', $grades->pluck('id'))
            ->with('extracurricular', 'achievement', 'health', 'attendances')
            ->get();

        //menyesuaikan sesuai hari
        $uniqueSickDates = [];
        $uniquePermissionDates = [];
        $uniqueUnexcusedDates = [];

        foreach ($rapors as $rapor) {
            foreach ($rapor->attendances as $attendance) {
                if ($attendance->sick > 0 && !in_array($attendance->date, $uniqueSickDates)) {
                    $uniqueSickDates[] = $attendance->date;
                }
                if ($attendance->permission > 0 && !in_array($attendance->date, $uniquePermissionDates)) {
                    $uniquePermissionDates[] = $attendance->date;
                }
                if ($attendance->unexcused > 0 && !in_array($attendance->date, $uniqueUnexcusedDates)) {
                    $uniqueUnexcusedDates[] = $attendance->date;
                }
            }
        }

        //hitung berasarkan hari yg berbeda
        $totalSick = count($uniqueSickDates);
        $totalPermission = count($uniquePermissionDates);
        $totalUnexcused = count($uniqueUnexcusedDates);

        // Set print_date
        foreach ($rapors as $rapor) {
            if (is_null($rapor->print_date)) {
                $rapor->print_date = now()->toDateString();
                $rapor->save();
            }
        }

        return view('rapors.index', [
            'student' => $student,
            'rapors' => $rapors,
            'semesters' => $semesters,
            'selectedSemesterYearId' => $selectedSemesterYearId,
            'sidebarOpen' => $sidebarOpen,
            'totalSick' => $totalSick,
            'totalPermission' => $totalPermission,
            'totalUnexcused' => $totalUnexcused,
            'headmaster' => $headmaster,
        ]);
    }

    //edit data kompetensi sikap
    public function editAspect($studentId, $raporId, $aspectName)
    {
        $student = Student::findOrFail($studentId);
        $rapor = Rapor::findOrFail($raporId);
        $action = route('rapors.updateAspect', ['studentId' => $studentId, 'raporId' => $raporId, 'aspectName' => $aspectName]);

        return view('rapors.editAspect', [
            'rapor' => $rapor,
            'action' => $action,
            'aspectName' => $aspectName,
            'student' => $student
        ]);
    }

    public function updateAspect(Request $request, $studentId, $raporId, $aspectName)
    {
        $validated = $request->validate([
            'social_attitudes' => 'nullable|string|max:255',
            'spiritual_attitude' => 'nullable|string|max:255',
        ]);

        try {
            // Cari rapor berdasarkan $raporId
            $rapor = Rapor::findOrFail($raporId);

            // Simpan nilai-nilai atribut kesehatan sesuai dengan aspek yang dipilih
            switch ($aspectName) {
                case 'Sikap Sosial':
                    $rapor->social_attitudes = $validated['social_attitudes'];
                    break;
                case 'Sikap Spiritual':
                    $rapor->spiritual_attitude = $validated['spiritual_attitude'];
                    break;
                default:
                    break;
            }

            $rapor->save();

            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Sikap Berhasil Disimpan';

            return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);

        } catch (\Exception $e) {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Gagal menyimpan data Sikap: ' . $e->getMessage();
            return redirect()->route('rapors.editAspect', ['studentId' => $studentId, 'raporId' => $raporId, 'aspectName' => $aspectName])->withInput()->with($notification);
        }
    }


     //saran
     public function editSuggestion($studentId, $semesterYearId)
     {
         $grade = Grade::where('student_id', $studentId)
                      ->where('semester_year_id', $semesterYearId)
                      ->firstOrFail();

         $rapor = Rapor::where('grade_id', $grade->id)->firstOrCreate(['grade_id' => $grade->id]);

         return view('rapors.edit-suggestion', [
             'rapor' => $rapor,
         ]);
     }

     public function updateSuggestion(Request $request, $studentId, $semesterYearId)
     {
         $validated = $request->validate([
             'suggestion' => 'required|max:255',
         ]);

         try {
             $grade = Grade::where('student_id', $studentId)
                          ->where('semester_year_id', $semesterYearId)
                          ->firstOrFail();

             $rapor = Rapor::where('grade_id', $grade->id)->firstOrCreate(['grade_id' => $grade->id]);

             $rapor->suggestion = $validated['suggestion'];
             $rapor->save();

             $notification['alert-type'] = 'success';
             $notification['message'] = 'Data Saran Berhasil Dimasukkan';
             return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);

         } catch (ModelNotFoundException $e) {
             $notification['alert-type'] = 'error';
             $notification['message'] = 'Data Saran Gagal Dimasukkan: ' . $e->getMessage();
             return redirect()->route('rapors.editSuggestion', ['studentId' => $studentId, 'semesterYearId' => $semesterYearId])->withInput()->with($notification);
         }
     }

     //edit nilai pengetahuan dan keterampilan
    public function edit($studentId, $semesterYearId)
    {
        $student = Student::findOrFail($studentId);
        $grade = Grade::where('student_id', $studentId)
            ->where('semester_year_id', $semesterYearId)
            ->firstOrFail();

        $classSubject = $grade->classSubject;
        $semesterYear = SemesterYear::findOrFail($semesterYearId);

        $rapor = Rapor::where('grade_id', $grade->id)->firstOrCreate([
            'grade_id' => $grade->id,
        ]);

        return view('rapors.edit', compact('student', 'classSubject', 'semesterYear', 'rapor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'average_knowledge_score' => 'nullable|numeric',
            'descriptionKnowledge' => 'nullable|string|max:255',
            'average_skill_score' => 'nullable|numeric',
            'descriptionSkill' => 'nullable|string|max:255',
        ]);

        $rapor = Rapor::findOrFail($id);
        $grade = $rapor->grade;

        if ($request->has('average_knowledge_score')) {
            $grade->average_knowledge_score = $request->average_knowledge_score;
            $grade->gradeKnowledge = $this->calculateGrade($request->average_knowledge_score);
        }

        if ($request->has('average_skill_score')) {
            $grade->average_skill_score = $request->average_skill_score;
            $grade->gradeSkill = $this->calculateGrade($request->average_skill_score);
        }

        $grade->descriptionKnowledge = $request->descriptionKnowledge;
        $grade->descriptionSkill = $request->descriptionSkill;

        $grade->save();

        return redirect()->route('rapors.index', ['studentId' => $rapor->grade->student_id])
            ->with('success', 'Rapor successfully updated.');
    }

    private function calculateGrade($value)
    {
        if ($value >= 90) {
            return 'A';
        } elseif ($value >= 80) {
            return 'B';
        } elseif ($value >= 70) {
            return 'C';
        } else {
            return 'D';
        }
    }

}
