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
        $rapor->status = 'validated';
        $rapor->save();


        if ($rapor) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Rapor Berhasil Divalidasi';
            return redirect()->route('rapors.validation.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Rapor Gagal Divalidasi';
            return redirect()->route('rapors.validation.index')->with($notification);
        }

    }

    public function reject($id)
{
    $rapor = Rapor::find($id);

        $rapor->status = 'rejected';
        $rapor->save();
        // dd('Reject method reached');

    return redirect()->route('rapors.validation.index')->with('success', 'Rapor telah ditolak.');
}

}
