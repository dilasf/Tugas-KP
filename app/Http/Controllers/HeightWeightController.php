<?php

namespace App\Http\Controllers;

use App\Models\HeightWeight;
use App\Models\Rapor;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class HeightWeightController extends Controller
{
    public function index()
    {
        $data = HeightWeight::all();
        $sidebarOpen = false;
        return view('height_weight.index', ['heightWeights' => $data], compact('sidebarOpen'));
    }
    public function create($studentId, $semester_year_id, $aspectName)
    {
        try {
            // Find the student
            $student = Student::findOrFail($studentId);

            // Retrieve rapor for the student and semester year
            $rapors = Rapor::where('student_id', $studentId)
                           ->where('semester_year_id', $semester_year_id)
                           ->get();

            // Handle scenario where no rapor is found
            if ($rapors->isEmpty()) {
                abort(404, 'Rapor not found for this student in the specified semester year.');
            }

            // Prepare action for form submission
            $action = route('height_weights.store', ['studentId' => $studentId, 'semester_year_id' => $semester_year_id, 'aspectName' => $aspectName]);

            return view('heightWeight.edit', [
                'student' => $student,
                'rapors' => $rapors,
                'aspectName' => $aspectName,
                'semester_year_id' => $semester_year_id,
                'action' => $action,
            ]);

        } catch (ModelNotFoundException $e) {
            abort(404, 'Student not found.');
        }
    }

    public function store(Request $request, $studentId, $semester_year_id, $aspectName)
    {
        $validated = $request->validate([
            'height' => 'nullable|integer',
            'weight' => 'nullable|integer',
            'head_size' => 'nullable|integer',
        ]);

        try {
            // Cari rapor yang sesuai dengan studentId dan semester_year_id
            $rapor = Rapor::whereHas('grade', function ($query) use ($studentId, $semester_year_id) {
                $query->where('student_id', $studentId)
                      ->where('semester_year_id', $semester_year_id);
            })->firstOrFail();

            // Buat atau perbaharui data tinggi badan
            $heightWeight = HeightWeight::create([
                'height' => $validated['height'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'head_size' => $validated['head_size'] ?? null,
            ]);

            // Asosiasikan heightWeight dengan rapor
            $rapor->height_weight_id = $heightWeight->id;
            $rapor->save();

            // Redirect dengan pesan sukses
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data tinggi badan berhasil disimpan';
            return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);

        } catch (ModelNotFoundException $e) {
            abort(404, 'Rapor not found.');
        } catch (\Exception $e) {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Gagal menyimpan data tinggi badan: ' . $e->getMessage();
            return redirect()->route('height_weights.create', ['studentId' => $studentId, 'semester_year_id' => $semester_year_id, 'aspectName' => $aspectName])->withInput()->with($notification);
        }
    }

    public function edit($studentId, $heightWeightId, $aspectName)
    {
        try {
            $student = Student::findOrFail($studentId);
            $heightWeight = HeightWeight::findOrFail($heightWeightId);

            $rapor = Rapor::where('height_weight_id', $heightWeightId)->first();

            return view('heightWeight.edit', [
                'studentId' => $studentId,
                'student' => $student,
                'heightWeight' => $heightWeight,
                'aspectName' => $aspectName,
                'rapor' => $rapor,
            ]);

        } catch (\Exception $e) {
            abort(404, 'Student or HeightWeight data not found.');
        }
    }

    public function update(Request $request, $studentId, $heightWeightId, $aspectName)
    {
        $validated = $request->validate([
            'height' => 'nullable|integer',
            'weight' => 'nullable|integer',
            'head_size' => 'nullable|integer',
        ]);

        try {
            $heightWeight = HeightWeight::findOrFail($heightWeightId);

            // Update berdasarkan aspek yang dipilih
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
                    abort(404, 'Invalid aspectName.');
                    break;
            }

            $heightWeight->save();

            // Redirect dengan pesan sukses
            return redirect()->route('rapors.index', ['studentId' => $studentId])
                ->with('success', 'Data tinggi badan berhasil diperbarui');

        } catch (\Exception $e) {
            abort(404, 'HeightWeight data not found.');
        }
    }
}
