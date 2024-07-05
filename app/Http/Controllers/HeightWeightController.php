<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeightWeight;
use App\Models\Rapor;
use App\Models\Grade;
use App\Models\SemesterYear;
use App\Models\Student;

class HeightWeightController extends Controller
{
    public function edit($studentId, $heightWeightId, $aspectName, $semester_year_id)
    {
        $student = Student::findOrFail($studentId);

        // Cari data HeightWeight yang sesuai dengan id
        $heightWeight = HeightWeight::findOrFail($heightWeightId);

        return view('heightWeight.edit', [
            'student' => $student,
            'heightWeight' => $heightWeight,
            'aspectName' => $aspectName,
            'semester_year_id' => $semester_year_id,
        ]);
    }

    public function update(Request $request, $studentId, $heightWeightId, $aspectName, $semester_year_id)
    {
        // Validate request
        $validated = $request->validate([
            'height' => 'nullable|integer',
            'weight' => 'nullable|integer',
            'head_size' => 'nullable|integer',
        ]);

        // Find the corresponding Grade ID for the given semester year and student
        $grade = Grade::where('student_id', $studentId)
            ->where('semester_year_id', $semester_year_id)
            ->first();

        if (!$grade) {
            // Handle the case where no Grade is found for the given semester and student
            return redirect()->route('height_weights.edit', [
                'studentId' => $studentId,
                'heightWeightId' => $heightWeightId,
                'aspectName' => $aspectName,
                'semester_year_id' => $semester_year_id,
            ])->withErrors(['error' => 'Grade not found for the given semester and student.']);
        }

        // Find or create Rapor
        $rapor = Rapor::firstOrCreate(['grade_id' => $grade->id]);

        // Find or create HeightWeight
        $heightWeight = HeightWeight::findOrFail($heightWeightId);
        $heightWeight->fill($validated);
        $heightWeight->rapor_id = $rapor->id;
        $heightWeight->save();

        return redirect()->route('rapors.index', ['studentId' => $studentId, 'semester_year_id' => $semester_year_id])
            ->with('success', 'Data tinggi berat badan berhasil diperbarui');
    }
}


// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\HeightWeight;
// use App\Models\Rapor;
// use App\Models\Grade;
// use App\Models\SemesterYear;
// use App\Models\Student;

// class HeightWeightController extends Controller
// {
// //     public function edit($studentId, $heightWeightId, $aspectName, Request $request)
// //     {
// //         $semester_year_id = $request->input('semester_year_id');
// //         $student = Student::findOrFail($studentId);

// //         $heightWeight = HeightWeight::with('rapor.grade')
// //             ->where('id', $heightWeightId)
// //             ->whereHas('rapor.grade', function ($query) use ($studentId) {
// //                 $query->where('student_id', $studentId);
// //             })
// //             ->first();

// //         if (!$heightWeight || !$heightWeight->rapor || !$heightWeight->rapor->grade) {
// //             $previousSemesterYearId = SemesterYear::where('id', '<', $semester_year_id)
// //                 ->orderByDesc('id')
// //                 ->first();

// //             if ($previousSemesterYearId) {
// //                 $heightWeight = HeightWeight::whereHas('rapor.grade', function ($query) use ($previousSemesterYearId, $studentId) {
// //                     $query->where('student_id', $studentId)
// //                           ->where('semester_year_id', $previousSemesterYearId->id);
// //                 })
// //                 ->first();
// //             }
// //         }

// //         return view('heightWeight.edit', [
// //             'student' => $student,
// //             'heightWeight' => $heightWeight,
// //             'semester_year_id' => $semester_year_id,
// //             'aspectName' => $aspectName,
// //         ]);
// //     }

// //     public function update(Request $request, $studentId, $heightWeightId, $aspectName)
// //     {
// //         $validated = $request->validate([
// //             'height' => 'nullable|integer',
// //             'weight' => 'nullable|integer',
// //             'head_size' => 'nullable|integer',
// //             'semester_year_id' => 'required|exists:semester_years,id',
// //         ]);

// //         // Find the corresponding Grade ID for the given semester year and student
// //         $grade = Grade::where('student_id', $studentId)
// //             ->where('semester_year_id', $validated['semester_year_id'])
// //             ->first();

// //         if (!$grade) {
// //             // Handle the case where no Grade is found for the given semester and student
// //             return redirect()->route('height_weights.edit', [
// //                 'studentId' => $studentId,
// //                 'heightWeightId' => $heightWeightId,
// //                 'aspectName' => $aspectName,
// //             ])->withErrors(['error' => 'Grade not found for the given semester and student.']);
// //         }

// //         $rapor = Rapor::where('grade_id', $grade->id)->first();

// //         if (!$rapor) {
// //             // Handle the case where no Rapor is found for the given grade
// //             return redirect()->route('height_weights.edit', [
// //                 'studentId' => $studentId,
// //                 'heightWeightId' => $heightWeightId,
// //                 'aspectName' => $aspectName,
// //             ])->withErrors(['error' => 'Rapor not found for the given grade.']);
// //         }

// //         $heightWeight = HeightWeight::where('rapor_id', $rapor->id)
// //             ->first();

// //         if (!$heightWeight) {
// //             // If no existing HeightWeight record, create a new one
// //             $heightWeight = new HeightWeight([
// //                 'student_id' => $studentId,
// //                 'rapor_id' => $rapor->id,
// //             ]);
// //         }

// //         switch ($aspectName) {
// //             case 'Tinggi Badan':
// //                 $heightWeight->height = $validated['height'];
// //                 break;
// //             case 'Berat Badan':
// //                 $heightWeight->weight = $validated['weight'];
// //                 break;
// //             case 'Ukuran Kepala':
// //                 $heightWeight->head_size = $validated['head_size'];
// //                 break;
// //             default:
// //                 return redirect()->route('height_weights.edit', [
// //                     'studentId' => $studentId,
// //                     'heightWeightId' => $heightWeightId,
// //                     'aspectName' => $aspectName,
// //                 ])->withErrors(['error' => 'Invalid aspectName.']);
// //         }

// //         $heightWeight->save();

// //         return redirect()->route('rapors.index', ['studentId' => $studentId, 'semester_year_id' => $validated['semester_year_id']])
// //             ->with('success', 'Data tinggi berat badan berhasil diperbarui');
// //     }
// // }

// namespace App\Http\Controllers;

// use App\Models\Grade;
// use Illuminate\Http\Request;
// use App\Models\HeightWeight;
// use App\Models\Rapor;
// use App\Models\SemesterYear;
// use App\Models\Student;

// class HeightWeightController extends Controller
// {
//     public function edit($studentId, $heightWeightId, $aspectName, Request $request)
//     {
//         $semester_year_id = $request->input('semester_year_id');
//         $student = Student::findOrFail($studentId);

//         $heightWeight = HeightWeight::with('rapor.grade')
//             ->where('id', $heightWeightId)
//             ->where('student_id', $studentId)
//             ->first();

//         // Cek apakah ada data tinggi berat badan yang tersedia
//         if (!$heightWeight || !$heightWeight->rapor || !$heightWeight->rapor->grade) {
//             // Jika tidak ada, cari semester sebelumnya untuk mengambil data terakhir
//             $previousSemesterYearId = SemesterYear::where('id', '<', $semester_year_id)
//                 ->orderByDesc('id')
//                 ->first();

//             if ($previousSemesterYearId) {
//                 $heightWeight = HeightWeight::whereHas('rapor.grade', function ($query) use ($previousSemesterYearId) {
//                     $query->where('semester_year_id', $previousSemesterYearId->id);
//                 })
//                 ->where('student_id', $studentId)
//                 ->first();
//             }
//         }

//         return view('heightWeight.edit', [
//             'student' => $student,
//             'heightWeight' => $heightWeight,
//             'semester_year_id' => $semester_year_id,
//             'aspectName' => $aspectName,
//         ]);
//     }

//     public function update(Request $request, $studentId, $heightWeightId = null, $aspectName)
//     {
//         $validatedData = $request->validate([
//             'height' => 'nullable|numeric',
//             'weight' => 'nullable|numeric',
//             'semester_year_id' => 'required|exists:semester_years,id',
//         ]);

//         // Temukan atau buat entri Grade sesuai dengan murid dan tahun semester
//         $grade = Grade::where('student_id', $studentId)
//                       ->where('semester_year_id', $validatedData['semester_year_id'])
//                       ->first();

//         // Temukan atau buat entri Rapor sesuai dengan grade
//         $rapor = Rapor::where('grade_id', $grade->id)->first();

//         // Update atau buat baru untuk data Tinggi Berat Badan
//         $heightWeight = HeightWeight::updateOrCreate(
//             ['id' => $heightWeightId],
//             [
//                 'student_id' => $studentId,
//                 'height' => $validatedData['height'] ?? null,
//                 'weight' => $validatedData['weight'] ?? null,
//                 'semester_year_id' => $validatedData['semester_year_id'],
//                 'rapor_id' => $rapor->id ?? null,
//             ]
//         );

//         // Redirect kembali ke halaman indeks rapor dengan notifikasi
//         return redirect()->route('rapors.index', ['studentId' => $studentId, 'semester_year_id' => $validatedData['semester_year_id']])
//                          ->with('success', 'Data berhasil disimpan.');
//     }
// }
