<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountTeacherController extends Controller
{
    public function index()
    {
        $teacherAccounts = Teacher::with('user')->get();
        $sidebarOpen = false;

        return view('account.teacher.index', compact('teacherAccounts', 'sidebarOpen'));
    }

    public function edit($id)
    {
        $teacherAccount = Teacher::with('user')->findOrFail($id);

        return view('account.teacher.edit', compact('teacherAccount'));
    }

    public function update(Request $request, $id)
    {
        $teacherAccount = Teacher::findOrFail($id);
        $user = $teacherAccount->user;

        $request->validate([
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'nuptk' => 'nullable|numeric|unique:teachers,nuptk,' . $teacherAccount->id,
            'nip' => 'nullable|numeric|unique:teachers,nip,' . $teacherAccount->id,
        ]);

        $user->update([
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
        ]);

        // Jika email di User berubah, update juga email di Teacher
        if ($request->input('email') !== null && $user->email !== $request->input('email')) {
            $teacherAccount->update([
                'email' => $request->input('email'),
            ]);
        }

        $teacherAccount->update([
            'nuptk' => $request->input('nuptk'),
            'nip' => $request->input('nip'),
        ]);

        return redirect()->route('account.teacher.index')->with('success', 'Akun guru berhasil diperbarui');
    }

}
