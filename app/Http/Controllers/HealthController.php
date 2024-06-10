<?php

namespace App\Http\Controllers;

use App\Models\Health;
use App\Models\Student;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function index()
    {
        $health = Health::with(['student'])->get();
        $sidebarOpen = false;
        return view('health.index', compact('healths','sidebarOpen'));
    }

    public function create()
    {
        $data['students'] = Student::pluck('student_name', 'id');
        return view('health.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'physical_aspect' => 'required|max:50',
            'description' => 'nullable|max:255',
            'student_id' => 'required|exists:students,id',
        ]);

        $data = Student::create($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Kesehatan Berhasil Disimpan';
            return redirect()->route('health.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Kesehatan Gagal Disimpan';
            return redirect()->route('health.create')->withInput()->with($notification);
        }
    }

    public function edit(string $id)
    {
        $data['healths'] = Health::find($id);
        $data['students'] = Student::pluck('student_name', 'id');

        return view('health.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $Health = Health::findOrFail($id);
        $validated = $request->validate([
            'physical_aspect' => 'required|max:50',
            'description' => 'nullable|max:255',
            'student_id' => 'required|max:255',
        ]);


        $data = $Health->update($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Kesehatan Berhasil Diperbaharui';
            return redirect()->route('health.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Kesehatan Gagal Diperbaharui';
            return redirect()->route('health.edit', $id)->withInput()->with($notification);
        }
    }

    public function destroy(string $id)
    {
        $Health = Health::findOrFail($id);

       $data = $Health->delete();
        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Kesehatan Berhasil Dihapus';
            return redirect()->route('health.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Kesehatan Gagal Dihapus';
            return redirect()->route('health.index')->withInput()->with($notification);
        }
    }
}
