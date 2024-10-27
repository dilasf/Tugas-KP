<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
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

        // memastikan email di tabel guru terupdate
        if ($request->input('email') !== null && $teacherAccount->mail !== $request->input('email')) {
            $teacherAccount->update([
                'mail' => $request->input('email'),
            ]);
        }

        $account = $teacherAccount->update([
            'nuptk' => $request->input('nuptk'),
            'nip' => $request->input('nip'),
        ]);

        if ($account) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Akun guru berhasil diperbarui';
            return redirect()->route('account.teacher.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Akun guru gagal diperbarui';
            return redirect()->route('account.teacher.edit')->withInput()->with($notification);
        }
    }


}
