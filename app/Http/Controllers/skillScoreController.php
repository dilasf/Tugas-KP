<?php

namespace App\Http\Controllers;

use App\Models\SkillScore;
use Illuminate\Http\Request;

class skillScoreController extends Controller
{
    public function index()
    {
        $assessmentTypes = SkillScore::select('id', 'assessment_type')->get()->toArray();
        $sidebarOpen = false;

        return view('grade.skill_scores.index', ['assessmentTypes' => $assessmentTypes], compact('sidebarOpen'));
    }


    public function create()
    {
        return view('grade.skill_scores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'assessment_type' => 'required|string|max:100|unique:skill_scores',
        ]);

        $data = SkillScore::create([
            'assessment_type' => $request->input('assessment_type'),
        ]);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Jenis Penilaian Berhasil Disimpan';
            return redirect()->route('grade.skill_scores.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Jenis Penilaian Gagal Disimpan';
            return redirect()->route('grade.skill_scores.create')->withInput()->with($notification);
        }
    }

    public function edit(string $id)
    {
        $assessmentType = SkillScore::findOrFail($id);
        return view('grade.skill_scores.edit', compact('assessmentType'));
    }

    public function update(Request $request, string $id)
    {
        $assessmentType = SkillScore::findOrFail($id);

        $request->validate([
            'assessment_type' => 'required|string|max:100',
        ]);


        $data = $assessmentType->update([
            'assessment_type' => $request->assessment_type,
        ]);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Jenis Penilaian Berhasil Diperbaharui';
            return redirect()->route('grade.skill_scores.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Jenis Penilaian Gagal Diperbaharui';
            return redirect()->route('grade.skill_scores.edit', $id)->withInput()->with($notification);
        }
    }

    public function destroy(string $id)
    {

        $assessmentType = SkillScore::findOrFail($id);
        $data = $assessmentType->delete();

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Jenis Penilaian Berhasil Dihapus';
            return redirect()->route('grade.skill_scores.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Jenis Penilaian Gagal Dihapus';
            return redirect()->route('grade.skill_scores.index', $id)->withInput()->with($notification);
        }
    }
}
