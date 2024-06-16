<?php

namespace App\Http\Controllers;

use App\Models\AttitudeScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttitudeScoreController extends Controller
{
    public function index()
    {
        $assessmentTypes = AttitudeScore::select('assessment_type')
                                        ->distinct()
                                        ->get();

        $sidebarOpen = false;

        return view('grade.attitude_scores.index', compact('assessmentTypes', 'sidebarOpen'));
    }


    public function create()
    {
        return view('grade.attitude_scores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'assessment_type' => 'required|string|max:100|unique:attitude_scores',
        ]);

        $data = AttitudeScore::create([
            'assessment_type' => $request->input('assessment_type'),
        ]);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Jenis Penilaian Berhasil Disimpan';
            return redirect()->route('grade.attitude_scores.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Jenis Penilaian Gagal Disimpan';
            return redirect()->route('grade.attitude_scores.create')->withInput()->with($notification);
        }
    }

    public function edit(string $assessment_type)
    {
        $assessmentType = AttitudeScore::where('assessment_type', $assessment_type)->firstOrFail();
        return view('grade.attitude_scores.edit', compact('assessmentType'));
    }

    public function update(Request $request, string $assessment_type)
    {
        $assessmentType = AttitudeScore::where('assessment_type', $assessment_type)->firstOrFail();

        $request->validate([
            'assessment_type' => 'required|string|max:100',
        ]);

        $data = $assessmentType->update([
            'assessment_type' => $request->assessment_type,
        ]);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Jenis Penilaian Berhasil Diperbaharui';
            return redirect()->route('grade.attitude_scores.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Jenis Penilaian Gagal Diperbaharui';
            return redirect()->route('grade.attitude_scores.edit', ['assessment_type' => $assessment_type])->withInput()->with($notification);
        }
    }

    public function destroy(string $assessment_type)
    {
        $assessmentType = AttitudeScore::where('assessment_type', $assessment_type)->firstOrFail();

        // Matikan constraint foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $data = $assessmentType->delete();

        // Aktifkan kembali constraint foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Jenis Penilaian Berhasil Dihapus';
            return redirect()->route('grade.attitude_scores.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Jenis Penilaian Gagal Dihapus';
            return redirect()->route('grade.attitude_scores.index')->with($notification);
        }
    }

}
