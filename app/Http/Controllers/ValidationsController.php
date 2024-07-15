<?php

namespace App\Http\Controllers;

use App\Models\Rapor;
use Illuminate\Http\Request;

class ValidationsController extends Controller
{
    public function index()
    {
        $waitingReports = Rapor::where('status', 'waiting_validation')
            ->with(['grade.student', 'grade.classSubject.class', 'grade.semesterYear'])
            ->get();

        return view('validation.index', compact('waitingReports'));
    }

    public function show($id)
    {
        $rapor = Rapor::with(['grade.student', 'grade.classSubject.class', 'grade.semesterYear'])
            ->findOrFail($id);

        return view('validation.detail', compact('rapor'));
    }

    public function approve($id)
    {
        $rapor = Rapor::findOrFail($id);
        $rapor->status = 'validated'; // Sesuaikan dengan status yang sesuai setelah divalidasi
        $rapor->save();

        return redirect()->route('rapors.validation.index')->with('success', 'Rapor berhasil divalidasi.');
    }
}
