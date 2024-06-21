<?php

namespace App\Http\Controllers;

use App\Models\Health;
use App\Models\Rapor;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function create($studentId, $semester_year_id, $aspectName)
    {
        try {
            $student = Student::findOrFail($studentId);

            // Ambil rapor yang sesuai dengan studentId dan semester_year_id
            $rapors = Rapor::whereHas('grade', function ($query) use ($studentId, $semester_year_id) {
                $query->where('student_id', $studentId)
                      ->where('semester_year_id', $semester_year_id);
            })->get();

            // Jika tidak ada rapor yang ditemukan, lemparkan 404
            if ($rapors->isEmpty()) {
                abort(404, 'Rapor not found for this student in the specified semester year.');
            }

            // Set action untuk form
            $action = route('healths.store', ['studentId' => $studentId, 'semester_year_id' => $semester_year_id, 'aspectName' => $aspectName]);

            return view('health.edit', [
                'student' => $student,
                'aspectName' => $aspectName,
                'semester_year_id' => $semester_year_id,
                'action' => $action, // set action untuk create
            ]);

        } catch (ModelNotFoundException $e) {
            abort(404, 'Student not found.');
        }
    }

    public function edit($studentId, $healthId, $aspectName)
{
    try {
        $student = Student::findOrFail($studentId);
        $health = Health::findOrFail($healthId);
        $semester_year_id = $health->rapor->grade->semester_year_id;

        $action = route('healths.update', ['studentId' => $studentId, 'healthId' => $healthId, 'aspectName' => $aspectName]); // default action is update

        return view('health.edit', [
            'student' => $student,
            'health' => $health, // Ensure $health is passed to the view
            'semester_year_id' => $semester_year_id,
            'aspectName' => $aspectName,
            'action' => $action,
        ]);
    } catch (ModelNotFoundException $e) {
        abort(404, 'Student or Health data not found.');
    }
}


    public function storeOrUpdate(Request $request, $studentId, $semester_year_id, $aspectName)
    {
        $validated = $request->validate([
            'hearing' => 'required_if:aspectName,Pendengaran|max:150',
            'vision' => 'required_if:aspectName,Penglihatan|max:150',
            'tooth' => 'required_if:aspectName,Gigi|max:150',
        ]);

        try {
            // Cari rapor yang sesuai dengan studentId dan semester_year_id
            $rapor = Rapor::whereHas('grade', function ($query) use ($studentId, $semester_year_id) {
                $query->where('student_id', $studentId)
                      ->where('semester_year_id', $semester_year_id);
            })->firstOrFail();

            // Cari data kesehatan berdasarkan aspek
            $health = Health::where('rapor_id', $rapor->id)->first();

            if (!$health) {
                // Jika data kesehatan belum ada, buat baru
                $health = new Health();
                $health->rapor_id = $rapor->id;
            }

            // Set nilai-nilai atribut kesehatan sesuai dengan aspek yang dipilih
            switch ($aspectName) {
                case 'Pendengaran':
                    $health->hearing = $validated['hearing'];
                    break;
                case 'Penglihatan':
                    $health->vision = $validated['vision'];
                    break;
                case 'Gigi':
                    $health->tooth = $validated['tooth'];
                    break;
                default:
                    // Handle default case if any
                    break;
            }

            // Simpan data kesehatan
            $health->save();

            // Redirect dengan pesan sukses
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Kesehatan Berhasil Disimpan';

            return redirect()->route('rapors.index', ['studentId' => $studentId])->with($notification);

        } catch (ModelNotFoundException $e) {
            abort(404, 'Rapor not found.');
        } catch (\Exception $e) {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Gagal menyimpan data Kesehatan: ' . $e->getMessage();
            return redirect()->route('healths.edit', ['studentId' => $studentId, 'semester_year_id' => $semester_year_id, 'aspectName' => $aspectName])->withInput()->with($notification);
        }
    }
}
