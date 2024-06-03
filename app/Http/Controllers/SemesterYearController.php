<?php

namespace App\Http\Controllers;

use App\Models\SemesterYear;
use Illuminate\Http\Request;

class SemesterYearController extends Controller
{
    public function index()
    {
        $data = SemesterYear::all();
        $sidebarOpen = false;
        return view('subject.semester_year.index', ['semester_years' => $data], compact('sidebarOpen'));
    }

    public function create()
    {
        return view('subject.semester_year.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester' => 'required|integer|digits:1',
            'year' => 'required|digits:4|integer|min:1900|max:' . (date('Y')),
        ]);

        // dd($request->all());

        $data = SemesterYear::create($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Tahun Ajaran Berhasil Disimpan';
            return redirect()->route('subject.semester_year.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Tahun Ajaran Gagal Disimpan';
            return redirect()->route('subject.semester_year.create')->withInput()->with($notification);
        }

    }

    public function edit(string $id)
    {
        $data['semester_years'] = SemesterYear::find($id);

        return view('subject.semester_year.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $subject = SemesterYear::findOrFail($id);
        $validated = $request->validate([
            'semester' => 'required|integer|digits:1',
            'year' => 'required|digits:4|integer|min:1900|max:' . (date('Y')),
        ]);


        $data = $subject->update($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Tahun Ajaran Berhasil Diperbaharui';
            return redirect()->route('subject.semester_year.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Tahun Ajaran Gagal Diperbaharui';
            return redirect()->route('subject.semester_year.edit', $id)->withInput()->with($notification);
        }
    }

    public function destroy(string $id)
    {
        $semesteryear = SemesterYear::findOrFail($id);
       //Storage::delete('public/cover_buku/'.$book->cover);

       $data = $semesteryear->delete();
        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Tahun Ajaran Berhasil Dihapus';
            return redirect()->route('subject.semester_year.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Tahun Ajaran Gagal Dihapus';
            return redirect()->route('subject.semester_year.create')->withInput()->with($notification);
        }
    }
}