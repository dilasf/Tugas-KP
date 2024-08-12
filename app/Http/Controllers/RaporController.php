<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\HeightWeight;
use App\Models\Rapor;
use App\Models\SemesterYear;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    public function index(Request $request, $studentId)
{

    $currentYear = now()->year;
    $currentMonth = now()->month;

    $defaultSemester = SemesterYear::where('year', $currentYear)
        ->where('semester', ($currentMonth >= 1 && $currentMonth <= 6) ? 1 : 2)
        ->first();

    $selectedSemesterYearId = $request->input('semester_year_id', $defaultSemester->id);

    // Mengambil semester hanya untuk tahun ini
    $semesters = SemesterYear::where('year', $currentYear)->get();
    $sidebarOpen = false;

    if (!$selectedSemesterYearId) {
        $selectedSemesterYearId = $defaultSemester->id;
    }

    // Menemukan data siswa
    $student = Student::with(['class.teacher', 'heightWeights'])->findOrFail($studentId);
    $headmaster = Teacher::where('typesOfCAR', 'Kepala Sekolah')->first();


    // Menemukan data grade sesuai semester
    $grades = Grade::where('student_id', $studentId)
        ->where('semester_year_id', $selectedSemesterYearId)
        ->get();

    // Sambungkan dengan tabel rapor
    $rapors = Rapor::whereIn('grade_id', $grades->pluck('id'))
        ->with('extracurricular', 'achievement', 'health', 'attendance')
        ->get();

    // Set print_date jika belum diatur
    foreach ($rapors as $rapor) {
        if (is_null($rapor->print_date)) {
            $rapor->print_date = now()->toDateString();
            $rapor->save();
        }
    }

    // Menyesuaikan sesuai hari
    $uniqueSickDates = [];
    $uniquePermissionDates = [];
    $uniqueUnexcusedDates = [];

    if (!empty($rapors)) {
        foreach ($rapors as $rapor) {
            if (!empty($rapor->attendance)) {
                $attendance = $rapor->attendance;

                if ($attendance->sick > 0 && !in_array($attendance->created_at->toDateString(), $uniqueSickDates)) {
                    $uniqueSickDates[] = $attendance->created_at->toDateString();
                }
                if ($attendance->permission > 0 && !in_array($attendance->created_at->toDateString(), $uniquePermissionDates)) {
                    $uniquePermissionDates[] = $attendance->created_at->toDateString();
                }
                if ($attendance->unexcused > 0 && !in_array($attendance->created_at->toDateString(), $uniqueUnexcusedDates)) {
                    $uniqueUnexcusedDates[] = $attendance->created_at->toDateString();
                }
            }
        }
    }

    // Hitung berdasarkan hari yang berbeda
    $totalSick = count($uniqueSickDates);
    $totalPermission = count($uniquePermissionDates);
    $totalUnexcused = count($uniqueUnexcusedDates);

    // Mengambil data heightWeight berdasarkan rapor yang ada
    $heightWeights = HeightWeight::whereIn('rapor_id', $rapors->pluck('id'))->get();

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
        'heightWeights' => $heightWeights,
    ]);
}


    //edit data kompetensi sikap
        public function createAspect($studentId, $aspectName)
    {
        $student = Student::findOrFail($studentId);
        $action = route('rapors.storeAspect', ['studentId' => $studentId, 'aspectName' => $aspectName]);

        return view('rapors.editAspect', [
            'rapor' => new Rapor(),
            'action' => $action,
            'aspectName' => $aspectName,
            'student' => $student
        ]);
    }

    public function storeAspect(Request $request, $studentId, $aspectName)
    {
        $validated = $request->validate([
            'social_attitudes' => 'nullable|string|max:255',
            'spiritual_attitude' => 'nullable|string|max:255',
        ]);

        // Create a new Rapor record
        $grade = Grade::where('student_id', $studentId)
                    ->where('semester_year_id', $request->input('semester_year_id'))
                    ->first();

        if ($grade) {
            $rapor = new Rapor();
            $rapor->grade_id = $grade->id;

            // Save attributes based on the aspectName
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
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Gagal menemukan Grade yang sesuai';
            return redirect()->route('rapors.createAspect', ['studentId' => $studentId, 'aspectName' => $aspectName])
                            ->withInput()
                            ->with($notification);
        }
    }


    public function editAspect($studentId, $raporId, $aspectName)
    {
        $student = Student::findOrFail($studentId);
        $rapor = Rapor::find($raporId);

        if (!$rapor) {
            return redirect()->route('rapors.createAspect', ['studentId' => $studentId, 'aspectName' => $aspectName]);
        }

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

        // Find the rapor record
        $rapor = Rapor::find($raporId);

        if ($rapor) {
            // Update attributes based on the aspectName
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
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Gagal menemukan Rapor yang sesuai';
            return redirect()->route('rapors.editAspect', ['studentId' => $studentId, 'raporId' => $raporId, 'aspectName' => $aspectName])
                            ->withInput()
                            ->with($notification);
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

        $grade = Grade::where('student_id', $studentId)
                    ->where('semester_year_id', $semesterYearId)
                    ->first();

        if ($grade) {
            $rapor = Rapor::updateOrCreate(
                ['grade_id' => $grade->id],
                ['suggestion' => $validated['suggestion']]
            );

            $notification = [
                'alert-type' => 'success',
                'message' => 'Data Saran Berhasil Dimasukkan'
            ];

            return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);
        } else {
            $notification = [
                'alert-type' => 'error',
                'message' => 'Gagal menemukan Grade yang sesuai'
            ];

            return redirect()->route('rapors.editSuggestion', ['studentId' => $studentId, 'semesterYearId' => $semesterYearId])
                            ->withInput()
                            ->with($notification);
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

    public function sendReport(Request $request, $raporId)
    {
        $rapor = Rapor::findOrFail($raporId);

        // Check if the status is 'not_sent' before updating
        if ($rapor->status === 'not_sent' || 'rejected') {
            $rapor->status = 'waiting_validation'; // Update status as needed
            $rapor->save();

            return redirect()->back()->with('success', 'Rapor berhasil dikirim.');
        }

        return redirect()->back()->with('error', 'Status rapor tidak valid untuk dikirim.');
    }




}
