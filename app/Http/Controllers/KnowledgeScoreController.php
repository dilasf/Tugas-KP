<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeScore;
use Illuminate\Http\Request;

class KnowledgeScoreController extends Controller
{
    public function index()
    {
        $assessmentTypes = KnowledgeScore::select('id', 'assessment_type')->get()->toArray();
        $sidebarOpen = false;

        return view('grade.knowledge_scores.index', ['assessmentTypes' => $assessmentTypes], compact('sidebarOpen'));
    }


    public function create()
    {
        return view('grade.knowledge_scores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'assessment_type' => 'required|string|max:100|unique:knowledge_scores',
        ]);

        $data = KnowledgeScore::create([
            'assessment_type' => $request->input('assessment_type'),
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


public function edit(string $id)
{
    $assessmentType = KnowledgeScore::findOrFail($id);
    return view('grade.knowledge_scores.edit', compact('assessmentType'));
}

public function update(Request $request, string $id)
{
    $assessmentType = KnowledgeScore::findOrFail($id);

    $request->validate([
        'assessment_type' => 'required|string|max:100',
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
        return redirect()->route('grade.knowledge_scores.edit', $id)->withInput()->with($notification);
    }
}

public function destroy(string $id)
{

    $assessmentType = KnowledgeScore::findOrFail($id);
    $data = $assessmentType->delete();

    if ($data) {
        $notification['alert-type'] = 'success';
        $notification['message'] = 'Jenis Penilaian Berhasil Dihapus';
        return redirect()->route('grade.knowledge_scores.index')->with($notification);
    } else {
        $notification['alert-type'] = 'error';
        $notification['message'] = 'Jenis Penilaian Gagal Dihapus';
        return redirect()->route('grade.knowledge_scores.index', $id)->withInput()->with($notification);
    }
}

}
