<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\Grade;
use App\Models\HeightWeight;
use App\Models\Rapor;
use App\Models\SemesterYear;
use App\Models\Student;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    public function index(Request $request, $studentId)
    {
        $selectedSemesterYearId = $request->input('semester_year_id', null);
        $semesters = SemesterYear::all();
        $sidebarOpen = false;

        $currentMonth = now()->month;
        $defaultSemester = $semesters->firstWhere('semester', ($currentMonth >= 1 && $currentMonth <= 6) ? 1 : 2);

        if (!$selectedSemesterYearId) {
            $selectedSemesterYearId = $defaultSemester->id;
        }

        $student = Student::with('heightWeight')->findOrFail($studentId);

        // Check if heightWeight is loaded and contains the necessary data
        if ($student->heightWeight) {
            $heightWeight = $student->heightWeight;
        } else {
            $heightWeight = null;
        }

        // Ambil data grades hanya untuk semester_year_id yang dipilih
        $grades = Grade::where('student_id', $studentId)
            ->where('semester_year_id', $selectedSemesterYearId)
            ->get();

        // Lakukan perhitungan grade dan simpan ke dalam setiap objek grade
        $grades->each(function($grade) {
            $gradeKnowledge = $this->calculateGrade($grade->average_knowledge_score);
            $gradeAttitude = $this->calculateGrade($grade->average_attitude_score);
            $gradeSkill = $this->calculateGrade($grade->average_skill_score);

            $grade->gradeKnowledge = $gradeKnowledge;
            $grade->descriptionKnowledge;
            $grade->gradeAttitude = $gradeAttitude;
            $grade->descriptionAttitude;
            $grade->gradeSkill = $gradeSkill;
            $grade->descriptionSkill;
            $grade->save();
        });

        // Ambil rapors hanya untuk grade_id yang terkait dengan grades yang telah diproses
        $rapors = Rapor::whereIn('grade_id', $grades->pluck('id'))
            ->with('extracurricular', 'achievement', 'health')
            ->get();

        return view('rapors.index',
        compact('student', 'rapors',
        'semesters', 'selectedSemesterYearId',
        'heightWeight', 'sidebarOpen'));
    }


    //saran
    public function editSuggestion($studentId)
    {
        $rapor = Rapor::whereHas('grade', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })->first();

        return view('rapors.edit-suggestion', [
            'rapor' => $rapor,
        ]);
    }


    public function updateSuggestion(Request $request, $studentId)
    {
        $validated = $request->validate([
            'suggestion' => 'required|max:255',
        ]);

        // Temukan rapor berdasarkan student_id dari tabel Grade
        $rapor = Rapor::whereHas('grade', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })->first();

        // Update atau tambahkan saran dengan data terverifikasi
        $rapor->suggestion = $validated['suggestion'];
        $rapor->save();
        if ($rapor) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Saran Berhasil Dimasukkan';
            return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Saran Gagal Dimasukkan';
            return redirect()->back()->with($notification);
        }


    }
    /**
     * Mendapatkan deskripsi dari predikat nilai.
     *
     * @param string $grade
     * @return string
     */
    // private function getGradeDescription($grade)
    // {
    //     switch ($grade) {
    //         case 'A':
    //             return 'Sangat baik';
    //         case 'B':
    //             return 'Baik';
    //         case 'C':
    //             return 'Cukup';
    //         case 'D':
    //             return 'Kurang';
    //         default:
    //             return 'Tidak ada deskripsi';
    //     }
    // }

//     public function edit($studentId, $semesterYearId)
//     {
//         // Temukan student berdasarkan studentId
//         $student = Student::findOrFail($studentId);

//         // Temukan grade yang sesuai dengan studentId dan semesterYearId
//         $grade = Grade::where('student_id', $studentId)
//             ->where('semester_year_id', $semesterYearId)
//             ->firstOrFail();

//         // Ambil classSubject berdasarkan grade yang ditemukan
//         $classSubject = $grade->classSubject;

//         // Ambil semesterYear berdasarkan semesterYearId
//         $semesterYear = SemesterYear::findOrFail($semesterYearId);

//         // Temukan atau buat data Rapor berdasarkan grade_id
//         $rapor = Rapor::where('grade_id', $grade->id)->first();

//         // Jika Rapor tidak ditemukan, buat baru
//         if (!$rapor) {
//             $rapor = new Rapor();
//             $rapor->grade_id = $grade->id;
//             // Tambahkan atribut lain yang perlu diisi berdasarkan kebutuhan
//             $rapor->save();
//         }

//         return view('rapors.edit', compact('student', 'classSubject', 'semesterYear', 'rapor'));
//     }


//     public function update(Request $request, $id)
// {
//     $request->validate([
//         'average_knowledge_score' => 'nullable|numeric',
//         'descriptionKnowledge' => 'nullable|string|max:255',
//         // 'average_attitude_score' => 'required|numeric',
//         // 'gradeAttitude' => 'required|string|max:1',
//         // 'descriptionAttitude' => 'nullable|string|max:255',
//         'average_skill_score' => 'nullable|numeric',
//         'descriptionSkill' => 'nullable|string|max:255',
//     ]);

//     $rapor = Rapor::findOrFail($id);
//     $grade = $rapor->grade;

//     // Update data pada grade berdasarkan data yang diterima dari request
//     if ($request->has('average_knowledge_score')) {
//         $grade->average_knowledge_score = $request->average_knowledge_score;
//         $grade->gradeKnowledge = $this->calculateGrade($request->average_knowledge_score);
//     }

//     if ($request->has('average_attitude_score')) {
//         $grade->average_attitude_score = $request->average_attitude_score;
//         $grade->gradeAttitude = $this->calculateGrade($request->average_attitude_score);
//     }

//     if ($request->has('average_skill_score')) {
//         $grade->average_skill_score = $request->average_skill_score;
//         $grade->gradeSkill = $this->calculateGrade($request->average_skill_score);
//     }

//     // $rapor->grade->average_attitude_score = $request->average_attitude_score;
//     // $rapor->grade->gradeAttitude = $request->gradeAttitude;
//     // $rapor->grade->descriptionAttitude = $request->descriptionAttitude;
//     $grade->descriptionKnowledge = $request->descriptionKnowledge;
//     $grade->descriptionAttitude = $request->descriptionAttitude;
//     $grade->descriptionSkill = $request->descriptionSkill;

//     $rapor->grade->save();

//     return redirect()->route('rapors.index', $rapor->grade->student_id)
//         ->with('success', 'Rapor successfully updated.');
// }

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

        return redirect()->route('rapors.index', ['id' => $rapor->grade->student_id])
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

    public function Extracurricular($studentId)
    {
        // Retrieve the student
        $student = Student::findOrFail($studentId);

        // Retrieve extracurriculars for the student
        $extracurriculars = Extracurricular::where('student_id', $studentId)->get();

        // Pass data to the view
        return view('rapors.index', [
            'student' => $student,
            'extracurriculars' => $extracurriculars,
        ]);
    }


}
