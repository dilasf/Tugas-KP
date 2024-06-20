<?php

namespace App\Http\Controllers;

use App\Models\HeightWeight;
use App\Models\Rapor;
use Illuminate\Http\Request;

class HeightWeightController extends Controller
{
    public function index()
    {
        $data = HeightWeight::all();
        $sidebarOpen = false;
        return view('height_weight.index', ['heightWeights' => $data], compact('sidebarOpen'));
    }

    public function create()
    {
        return view('height_weight.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'height' => 'nullable|integer|digits_between:1,3',
            'weight' => 'nullable|integer|digits_between:1,3',
            'head_size' => 'nullable|integer|digits_between:1,3',
        ]);

        $data = HeightWeight::create($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Tinggi dan Berat Badan Berhasil Dihapus';
            return redirect()->route('student_data.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Tinggi dan Berat Badan Gagal Disimpan';
            return redirect()->route('student_data.create')->withInput()->with($notification);
        }
    }

    public function edit($id, Request $request)
    {
        $selectedSemesterYearId = $request->input('semester_year_id');
        $rapor = Rapor::with(['grade.student', 'heightWeight'])->findOrFail($id);
        $heightWeight = $rapor->heightWeight;

        // Mendapatkan student dari relasi
        $student = $rapor->grade->student;

        return view('heightWeight.edit', compact('rapor', 'heightWeight', 'selectedSemesterYearId', 'student'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'height' => 'required|integer',
            'weight' => 'required|integer',
            'head_size' => 'required|integer',
        ]);

        $rapor = Rapor::findOrFail($id);

        // Update atau buat baru untuk HeightWeight
        if ($rapor->heightWeight) {
            $rapor->heightWeight->update($request->only('height', 'weight', 'head_size'));
        } else {
            $heightWeight = HeightWeight::create($request->only('height', 'weight', 'head_size'));
            $rapor->height_weight_id = $heightWeight->id;
            $rapor->save();
        }

        return redirect()->route('height_weights.edit', ['id' => $rapor->id, 'semester_year_id' => $request->input('semester_year_id')])
                         ->with('success', 'Height and weight updated successfully.');
    }
}
