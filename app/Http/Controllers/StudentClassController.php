<?php

namespace App\Http\Controllers;

use App\Models\StudentClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class StudentClassController extends Controller
{

    public function index()
    {
        $data = StudentClass::with('teacher')->get();
        $sidebarOpen = false;
        return view('class.index', ['classes' => $data], compact('sidebarOpen'));

    }

    public function create()
    {
        $data['teacher'] = Teacher::pluck('teacher_name', 'id');
        return view('class.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|max:50|unique:classes',
            'level' => 'required|integer|unique:classes',
            'number_of_male_students' => 'nullable|integer',
            'number_of_female_students' => 'nullable|integer',
            'number_of_students' => 'nullable|integer',
            'homeroom_teacher_id' => 'required|exists:teachers,id',
            'curriculum' => 'required|max:255',
            'room' => 'required|max:100',
        ]);

        $class = StudentClass::create($validated);

        $class->total_students = $validated['number_of_students'];
        $class->number_of_male_students = $validated['number_of_male_students'];
        $class->number_of_female_students = $validated['number_of_female_students'];
        $class->save();

        if ($class) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Kelas Berhasil Disimpan';
            return redirect()->route('class.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Kelas Gagal Disimpan';
            return redirect()->route('class.create')->withInput()->with($notification);
        }
    }

    public function edit(string $id)
    {
        $data['classes'] = StudentClass::find($id);
        $data['teachers'] = Teacher::pluck('teacher_name', 'id');

        return view('class.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $class = StudentClass::findOrFail($id);
        $validated = $request->validate([
            'class_name' => 'required|max:50|unique:classes,class_name,' . $class->id,
            'level' => 'required|integer|unique:classes,level,' . $class->id,
            'number_of_male_students' => 'nullable|integer',
            'number_of_female_students' => 'nullable|integer',
            'number_of_students' => 'nullable|integer',
            'homeroom_teacher' => 'required|max:255',
            'curriculum' => 'required|max:255',
            'room' => 'required|max:100',
        ]);


        $data = $class->update($validated);

        // $class->total_students = $validated['number_of_students'];
        // $class->number_of_male_students = $validated['number_of_male_students'];
        // $class->number_of_female_students = $validated['number_of_female_students'];
        // $class->save();

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Kelas Berhasil Diperbaharui';
            return redirect()->route('class.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Kelas Gagal Diperbaharui';
            return redirect()->route('class.edit', $id)->withInput()->with($notification);
        }
    }

    public function destroy(string $id)
    {
        $class = StudentClass::findOrFail($id);
       //Storage::delete('public/cover_buku/'.$book->cover);

       $class->total_students = 0;
        $class->number_of_male_students = 0;
        $class->number_of_female_students = 0;
        $class->save();

       $data = $class->delete();
        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Kelas Berhasil Dihapus';
            return redirect()->route('class.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Kelas Gagal Dihapus';
            return redirect()->route('class.index')->withInput()->with($notification);
        }
    }
}