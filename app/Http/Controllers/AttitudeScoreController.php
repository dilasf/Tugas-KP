<?php

namespace App\Http\Controllers;

use App\Models\AttitudeScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttitudeScoreController extends Controller
{
    public function index()
    {
        $assessmentTypes = AttitudeScore::select('id', 'assessment_type')->get()->toArray();
        $sidebarOpen = false;

        return view('grade.attitude_scores.index', ['assessmentTypes' => $assessmentTypes], compact('sidebarOpen'));
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

    public function edit(string $id)
    {
        $assessmentType = AttitudeScore::findOrFail($id);
        return view('grade.attitude_scores.edit', compact('assessmentType'));
    }

    public function update(Request $request, string $id)
    {
        $assessmentType = AttitudeScore::findOrFail($id);

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
            return redirect()->route('grade.attitude_scores.edit', $id)->withInput()->with($notification);
        }
    }

    public function destroy(string $id)
    {
        $assessmentType = AttitudeScore::findOrFail($id);

        // Matikan constraint foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Hapus baris di tabel attitude_scores
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
            return redirect()->route('grade.attitude_scores.index', $id)->withInput()->with($notification);
        }
    }
}
