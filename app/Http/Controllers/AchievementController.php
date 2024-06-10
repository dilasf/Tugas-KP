<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Student;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        $achievement = Achievement::with(['student'])->get();
        $sidebarOpen = false;
        return view('achievement.index', compact('achievements','sidebarOpen'));
    }

    public function create()
    {
        $data['students'] = Student::pluck('student_name', 'id');
        return view('achievement.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'achievement_type' => 'required|max:200',
            'description' => 'nullable|max:255',
            'student_id' => 'required|exists:students,id',
        ]);

        $data = Student::create($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Prestasi Berhasil Disimpan';
            return redirect()->route('achievement.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Prestasi Gagal Disimpan';
            return redirect()->route('achievement.create')->withInput()->with($notification);
        }
    }

    public function edit(string $id)
    {
        $data['achievements'] = Achievement::find($id);
        $data['students'] = Student::pluck('student_name', 'id');

        return view('achievement.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $achievement = Achievement::findOrFail($id);
        $validated = $request->validate([
            'achievement_type' => 'required|max:200',
            'description' => 'nullable|max:255',
            'student_id' => 'required|max:255',
        ]);


        $data = $achievement->update($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Prestasi Berhasil Diperbaharui';
            return redirect()->route('achievement.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Prestasi Gagal Diperbaharui';
            return redirect()->route('achievement.edit', $id)->withInput()->with($notification);
        }
    }

    public function destroy(string $id)
    {
        $achievement = Achievement::findOrFail($id);

       $data = $achievement->delete();
        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Prestasi Berhasil Dihapus';
            return redirect()->route('achievement.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Prestasi Gagal Dihapus';
            return redirect()->route('achievement.index')->withInput()->with($notification);
        }
    }
}
