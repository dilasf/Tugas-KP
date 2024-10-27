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
        $studentAccount = Student::with(['user', 'guardian'])->findOrFail($id);

        return view('account.student.edit', compact('studentAccount'));
    }

    public function update(Request $request, $id)
    {
        $studentAccount = Student::with(['user', 'guardian'])->findOrFail($id);
        $user = $studentAccount->user;

        $request->validate([
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'nis' => 'nullable|numeric|unique:students,nis,' . $studentAccount->id,
            'nisn' => 'nullable|numeric|unique:students,nisn,' . $studentAccount->id,
        ]);

        // Cek apakah email yang diinput berbeda dari email lama user
        $emailChanged = $request->input('email') && $user->email !== $request->input('email');

        // Update email dan password user
        $user->update([
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
        ]);

        // Update email di guardian jika email berubah dan student memiliki guardian
        if ($emailChanged && $studentAccount->guardian) {
            $guardian = $studentAccount->guardian;

            if ($guardian->parent_email) {
                // Jika parent_email ada, update parent_email
                $guardian->update([
                    'parent_email' => $request->input('email')
                ]);
            } elseif ($guardian->guardian_email) {
                // Jika guardian_email ada tetapi parent_email kosong, update guardian_email
                $guardian->update([
                    'guardian_email' => $request->input('email')
                ]);
            }
        }

        $studentUpdated = $studentAccount->update();

        if ($studentUpdated) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Akun siswa berhasil diperbarui';
            return redirect()->route('account.student.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Akun siswa gagal diperbarui';
            return redirect()->route('account.student.edit')->withInput()->with($notification);
        }
    }



    // public function update(Request $request, $id)
    // {
    //     $studentAccount = Student::with(['user', 'guardian'])->findOrFail($id);
    //     $user = $studentAccount->user;

    //     $request->validate([
    //         'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
    //         'password' => 'nullable|string|min:8',
    //         'nis' => 'nullable|numeric|unique:students,nis,' . $studentAccount->id,
    //         'nisn' => 'nullable|numeric|unique:students,nisn,' . $studentAccount->id,
    //     ]);

    //     $user->update([
    //         'email' => $request->input('email'),
    //         'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
    //     ]);

    //     // Jika email di User berubah, update juga email di Student
    //     // if ($request->input('email') !== null && $user->email !== $request->input('email')) {
    //     //     $studentAccount->update([
    //     //         'email' => $request->input('email'),
    //     //     ]);
    //     // }

    //         // Get the guardian's email
    // $guardianEmail = null;
    // if ($studentAccount->guardian) {
    //     $guardianEmail = $studentAccount->guardian->guardian_email; // Email from guardian
    // }

    // // If the guardian is not present, check if we have parents' emails
    // if ($guardianEmail === null) {
    //     $guardianEmail = $studentAccount->guardian->parent_email; // Email from biological parent
    // }

    // // If there's a guardian email and it differs from the user's email, update the student's email
    // if ($guardianEmail && $user->email !== $guardianEmail) {
    //     $studentAccount->update([
    //         'email' => $guardianEmail, // Update student email to guardian email
    //     ]);
    // }


    //     $account = $studentAccount->update();

    //     if ($account) {
    //         $notification['alert-type'] = 'success';
    //         $notification['message'] = 'Akun siswa berhasil diperbarui';
    //         return redirect()->route('account.student.index')->with($notification);
    //     } else {
    //         $notification['alert-type'] = 'error';
    //         $notification['message'] = 'Akun siswa gagal diperbarui';
    //         return redirect()->route('account.student.edit')->withInput()->with($notification);
    //     }
    // }
}
