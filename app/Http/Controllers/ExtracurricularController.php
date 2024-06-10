<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\Student;
use Illuminate\Http\Request;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $extracurricular = Extracurricular::with(['student'])->get();
        $sidebarOpen = false;
        return view('extracurricular.index', compact('healths','sidebarOpen'));
    }

    public function create()
    {
        $data['students'] = Student::pluck('student_name', 'id');
        return view('extracurricular.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity' => 'required|max200',
            'description' => 'nullable|max:255',
            'student_id' => 'required|exists:students,id',
        ]);

        $data = Student::create($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Ekstrakulikuler Berhasil Disimpan';
            return redirect()->route('extracurricular.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Ekstrakulikuler Gagal Disimpan';
            return redirect()->route('extracurricular.create')->withInput()->with($notification);
        }
    }

    public function edit(string $id)
    {
        $data['extracurriculars'] = Extracurricular::find($id);
        $data['students'] = Student::pluck('student_name', 'id');

        return view('extracurricular.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        $validated = $request->validate([
            'activity' => 'required|max:200',
            'description' => 'nullable|max:255',
            'student_id' => 'required|max:255',
        ]);


        $data = $extracurricular->update($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Ekstrakulikuler Berhasil Diperbaharui';
            return redirect()->route('extracurricular.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Ekstrakulikuler Gagal Diperbaharui';
            return redirect()->route('extracurricular.edit', $id)->withInput()->with($notification);
        }
    }

    public function destroy(string $id)
    {
        $extracurricular = Extracurricular::findOrFail($id);

       $data = $extracurricular->delete();
        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Ekstrakulikuler Berhasil Dihapus';
            return redirect()->route('extracurricular.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Ekstrakulikuler Gagal Dihapus';
            return redirect()->route('extracurricular.index')->withInput()->with($notification);
        }
    }
}
