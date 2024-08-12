<?php

namespace App\Http\Controllers;

use App\Models\AccountStudent;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountStudentController extends Controller
{
    public function index()
    {
        $studentAccounts = Student::with('user')->get();
        $sidebarOpen = false;

        return view('account.student.index', compact('studentAccounts', 'sidebarOpen'));
    }

    public function edit($id)
    {
        $studentAccount = Student::with('user')->findOrFail($id);

        return view('account.student.edit', compact('studentAccount'));
    }

    public function update(Request $request, $id)
    {
        $studentAccount = Student::findOrFail($id);
        $user = $studentAccount->user;

        $request->validate([
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'nis' => 'nullable|numeric|unique:students,nis,' . $studentAccount->id,
            'nisn' => 'nullable|numeric|unique:students,nisn,' . $studentAccount->id,
        ]);

        $user->update([
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
        ]);

        // Jika email di User berubah, update juga email di Student
        if ($request->input('email') !== null && $user->email !== $request->input('email')) {
            $studentAccount->update([
                'email' => $request->input('email'),
            ]);
        }

        $studentAccount->update([
            'nis' => $request->input('nis'),
            'nisn' => $request->input('nisn'),
        ]);

        return redirect()->route('account.student.index')->with('success', 'Akun siswa berhasil diperbarui');
    }
}
