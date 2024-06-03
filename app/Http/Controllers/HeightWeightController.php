<?php

namespace App\Http\Controllers;

use App\Models\HeightWeight;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeightWeightController extends Controller
{
    public function index()
    {
        $data = HeightWeight::all();
        $sidebarOpen = false;
        return view('height_weight.index', ['heightWeights' => $data], compact('sidebarOpen'));
    }

    // public function dashboard()
    // {
    //     $heightWeightCount = HeightWeight::count();
    //     return view('dashboard', compact('heightWeightCount'));
    // }

    public function create()
    {
        return view('height_weight.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'height' => 'nullable|integer|digits_between:1,3',
            'weight' => 'nullable|integer|digits_between:1,3',
            'head_size' => 'nullable|integer|digits_between:1,3',
        ]);

        $data = HeightWeight::create($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Tinggi dan Berat Badan Berhasil Dihapus';
            return redirect()->route('student_data.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Tinggi dan Berat Badan Gagal Disimpan';
            return redirect()->route('student_data.create')->withInput()->with($notification);
        }
    }

    public function edit(string $id)
    {
        $data['heightWeight'] = HeightWeight::find($id);

        return view('height_weight.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $heightWeight = HeightWeight::findOrFail($id);
        $validated = $request->validate([
            'height' => 'nullable|integer|digits_between:1,3',
            'weight' => 'nullable|integer|digits_between:1,3',
            'head_size' => 'nullable|integer|digits_between:1,3',
        ]);

        $data = $heightWeight->update($validated);

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Tinggi dan Berat Badan Berhasil Diperbaharui';
            return redirect()->route('student_data.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Tinggi dan Berat Badan Gagal Diperbaharui';
            return redirect()->route('student_data.edit', $id)->withInput()->with($notification);
        }
    }

    public function destroy(string $id)
    {
        $heightWeight = HeightWeight::findOrFail($id);

        $data = $heightWeight->delete();
        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Tinggi dan Berat Badan Berhasil Disimpan';
            return redirect()->route('student_data.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Tinggi dan Berat Badan Gagal Disimpan';
            return redirect()->route('student_data.index')->with($notification);
        }
    }
}
