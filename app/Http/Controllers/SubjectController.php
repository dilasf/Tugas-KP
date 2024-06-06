<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $data = Subject::all();
        $sidebarOpen = false;
        return view('subject.index', ['subjects' => $data], compact('sidebarOpen'));
    }

    public function create()
    {
        return view('subject.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|max:150',
            'kkm' => 'required|numeric|digits_between:1,100',
        ]);


        $data = Subject::create($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Mata Pelajaran Berhasil Disimpan';
            return redirect()->route('subject.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Mata Pelajaran Gagal Disimpan';
            return redirect()->route('subject.create')->withInput()->with($notification);
        }

    }

    public function edit(string $id)
    {
        $data['subjects'] = Subject::find($id);

        return view('subject.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);
        $validated = $request->validate([
            'subject_name' => 'required|max:150',
            'kkm' => 'required|numeric|digits_between:1,100',
        ]);


        $data = $subject->update($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Mata Pelajaran Berhasil Diperbaharui';
            return redirect()->route('subject.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Mata Pelajaran Gagal Diperbaharui';
            return redirect()->route('subject.edit', $id)->withInput()->with($notification);
        }
    }

    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);

       $data = $subject->delete();
        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Mata Pelajaran Berhasil Dihapus';
            return redirect()->route('subject.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Mata Pelajaran Gagal Dihapus';
            return redirect()->route('subject.create')->withInput()->with($notification);
        }
    }
}
