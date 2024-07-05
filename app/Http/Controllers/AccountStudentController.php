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

    public function createStudentAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'parent_email' => 'nullable|string|email|max:255|unique:guardians,parent_email',
            'nis' => 'required|numeric|unique:students',
            'student_name' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'student',
        ]);

        $guardian = Guardian::create([
            'user_id' => $user->id,
            'parent_email' => $request->input('parent_email'),
            // atribut lainnya dari tabel guardians
        ]);

        Student::create([
            'user_id' => $user->id,
            'nis' => $request->input('nis'),
            'student_name' => $request->input('student_name'),
            // atribut lainnya dari tabel students
        ]);

        return response()->json(['message' => 'Akun siswa berhasil dibuat'], 201);
    }
}
