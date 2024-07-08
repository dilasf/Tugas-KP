<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KnowledgeScoreController extends Controller
{

    public function index()
    {
        $teacher = Auth::user();
        $assessmentTypes = KnowledgeScore::where('teacher_id', $teacher->teacher_id)
                                            ->select('assessment_type')
                                            ->distinct()
                                            ->get();
        $sidebarOpen = false;

        return view('grade.knowledge_scores.index', compact('assessmentTypes', 'sidebarOpen'));
    }

    public function create()
    {
        return view('grade.knowledge_scores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'assessment_type' => 'required|string|max:100|unique:knowledge_scores,assessment_type,NULL,id,teacher_id,' . Auth::user()->teacher_id,
        ]);

        $user = Auth::user();

        if (!$user->teacher_id) {
            return back()->withErrors(['message' => 'Pengguna Tidak Terdaftar']);
        }

        $data = KnowledgeScore::create([
            'assessment_type' => $request->input('assessment_type'),
            'teacher_id' => $user->teacher_id,
        ]);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Jenis Penilaian Berhasil Disimpan';
            return redirect()->route('grade.knowledge_scores.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Jenis Penilaian Gagal Disimpan';
            return redirect()->route('grade.knowledge_scores.create')->withInput()->with($notification);
        }
    }


    public function edit(string $assessment_type)
    {
        $assessmentType = KnowledgeScore::where('assessment_type', $assessment_type)->firstOrFail();
        return view('grade.knowledge_scores.edit', compact('assessmentType'));
    }


    public function update(Request $request, string $assessment_type)
    {
        $assessmentType = KnowledgeScore::where('assessment_type', $assessment_type)->firstOrFail();

        $request->validate([
            'assessment_type' => 'required|string|max:100|unique:knowledge_scores,assessment_type,' . $assessmentType->id,
        ]);

        $data = $assessmentType->update([
            'assessment_type' => $request->assessment_type,
        ]);
    if ($data) {
        $notification['alert-type'] = 'success';
        $notification['message'] = 'Jenis Penilaian Berhasil Diperbaharui';
        return redirect()->route('grade.knowledge_scores.index')->with($notification);
    } else {
        $notification['alert-type'] = 'error';
        $notification['message'] = 'Jenis Penilaian Gagal Diperbaharui';
        return redirect()->route('grade.knowledge_scores.edit', ['assessment_type' => $assessment_type])->withInput()->with($notification);
    }
    }

    public function destroy(string $assessment_type)
    {
        $deleted = KnowledgeScore::where('assessment_type', $assessment_type)->delete();

        if ($deleted) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Jenis Penilaian Berhasil Dihapus';
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Jenis Penilaian Gagal Dihapus';
        }

        return redirect()->route('grade.skill_scores.index')->with($notification);
    }

}
