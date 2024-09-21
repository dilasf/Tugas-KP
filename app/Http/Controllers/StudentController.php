<?php

namespace App\Http\Controllers;

use App\Imports\StudentsDataImport;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\HeightWeight;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    // Menampilkan daftar siswa
    // public function index()
    // {
    //     $students = Student::with(['class', 'guardian', 'latestHeightWeight'])->get();
    //     $sidebarOpen = false;

    //     return view('student_data.index', compact('students', 'sidebarOpen'));
    // }

    public function index()
{
    $user = Auth::user();
    $teacher = $user->teacher;
    $roleId = $user->role_id;
    $sidebarOpen = false;

    if ($roleId == 4) { // Guru Kelas
        $classId = StudentClass::where('homeroom_teacher_id', $teacher->id)->pluck('id')->first();

        // Ambil semua siswa di kelas yang diajar oleh guru kelas
        $students = Student::with(['class', 'guardian', 'latestHeightWeight'])
            ->where('class_id', $classId)
            ->get();
    } elseif ($roleId == 3) { // Guru Mapel
        // Ambil semua siswa dan urutkan berdasarkan kelas dan NIPD
        $students = Student::with(['class', 'guardian', 'latestHeightWeight'])
            ->orderBy('class_id')
            ->orderBy('nipd')
            ->get();
    } elseif ($roleId == 1) { // Admin
        // Ambil semua siswa tanpa filter
        $students = Student::with(['class', 'guardian', 'latestHeightWeight'])
            ->orderBy('class_id')
            ->orderBy('nipd')
            ->get();
    } else {
        // Jika role_id tidak sesuai
        return abort(403, 'Unauthorized action.');
    }

    return view('student_data.index', compact('students', 'sidebarOpen'));
}


    // Menampilkan detail informasi siswa
    public function show($id)
    {
        $student = Student::with(['guardian'])->findOrFail($id);
        $latestHeight = $student->latestNonNullHeight();
        $latestWeight = $student->latestNonNullWeight();
        $latestHeadSize = $student->latestNonNullHeadSize();

        return view('student_data.show-detail', compact('student', 'latestHeight', 'latestWeight', 'latestHeadSize'));
    }

    // Menampilkan form data orang tua
    public function createParent()
    {
        return view('student_data.create-parent');
    }

    // Menyimpan data orang tua ke session
    public function storeParent(Request $request)
    {
        $validated = $request->validate([
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'father_nik' => 'nullable|numeric|unique:guardians,father_nik',
            'mother_nik' => 'nullable|numeric|unique:guardians,mother_nik',
            'father_birth_year' => 'nullable|date',
            'mother_birth_year' => 'nullable|date',
            'father_education' => 'nullable|string|max:255',
            'mother_education' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'father_income' => 'nullable|max:100',
            'mother_income' => 'nullable|max:100',
            'parent_phone_number' => 'nullable|numeric|unique:guardians,parent_phone_number',
            'parent_email' => 'nullable|email|unique:guardians,parent_email|max:255',
        ]);

        // Simpan data ke dalam session
        $request->session()->put('parent_data', $validated);

        return redirect()->route('student_data.create');
    }

    // Menampilkan form data wali
    public function createGuardian()
    {
        return view('student_data.create-guardian');
    }

    // Menyimpan data wali ke session
    public function storeGuardian(Request $request)
    {
        $validated = $request->validate([
            'guardian_name' => 'nullable|string|max:255',
            'guardian_nik' => 'nullable|numeric|unique:guardians,guardian_nik',
            'guardian_birth_year' => 'nullable|date',
            'guardian_education' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_income' => 'nullable|max:100',
            'guardian_phone_number' => 'nullable|numeric|unique:guardians,guardian_phone_number',
            'guardian_email' => 'nullable|email|unique:guardians,guardian_email|max:255',
        ]);

        // Simpan data ke dalam session
        $request->session()->put('guardian_data', $validated);

        return redirect()->route('student_data.create');
    }

    // Menampilkan form data siswa
    public function create()
    {
        $height_weights = HeightWeight::all();
        $guardians = Guardian::all();
        $classes = StudentClass::all();

        return view('student_data.create', compact('height_weights', 'guardians', 'classes'));
    }

    // Menyimpan data siswa dan data terkait ke dalam database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_photo' => 'nullable|image',
            'status' => 'required|boolean',
            'nis' => 'required|unique:students|numeric|digits_between:1,11',
            'nisn' => 'required|unique:students|numeric|digits_between:1,11',
            'nipd' => 'required|unique:students|numeric|digits_between:1,10',
            'class_id' => 'required|exists:classes,id',
            'student_name' => 'required|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|unique:students|numeric|digits_between:1,17',
            'place_of_birth' => 'required|max:50',
            'date_of_birth' => 'required|date',
            'religion' => 'required|max:10',
            'address' => 'required|max:255',
            'special_needs' => 'nullable',
            'previous_school' => 'nullable|max:255',
            'birth_certificate_number' => 'nullable|max:60',
            'residence_type' => 'nullable|max:25',
            'no_kk' => 'required|unique:students|numeric|digits_between:1,17',
            'child_number' => 'nullable|numeric|digits_between:1,2',
            'number_of_siblings' => 'nullable|numeric|digits_between:1,2',
            'transportation' => 'nullable|max:20',
            'distance_to_school' => 'nullable|numeric|digits_between:1,2',
        ]);

        // Simpan foto siswa jika ada
        if ($request->hasFile('student_photo')) {
            $path = $request->file('student_photo')->storeAs(
                'public/photos',
                'student_photo_' . time() . '.' . $request->file('student_photo')->extension()
            );
            $validated['student_photo'] = basename($path);
        }

        // Validasi dan simpan data berat badan
        $heightWeightData = $request->validate([
            'height' => 'nullable|integer',
            'weight' => 'nullable|integer',
            'head_size' => 'nullable|integer',
        ]);

        // Ambil data dari session
        $parentData = $request->session()->get('parent_data', []);
        $guardianData = $request->session()->get('guardian_data', []);

        // Gabungkan data parent dan guardian
        $mergedData = array_merge($parentData, $guardianData);

        // Simpan guardian ke database jika data baru
        $guardian = Guardian::create($mergedData);

        // Simpan siswa ke database dengan relasi guardian
        $student = Student::create(array_merge($validated, [
            'guardian_id' => $guardian->id,
        ]));

        // Membuat user terkait
        $user = User::create([
            'student_id' => $student->id,
            'name' => $validated['student_name'],
            'email' => $validated['student_name']. '@example.com',
            'nis' => $validated['nis'],
            // 'nisn' => $validated['nisn'],
            'password' => Hash::make('password123'),
            'role_id' => 5, // Set role_id to 5 for student
        ]);

        // Simpan data berat badan ke database
        $heightWeightData['student_id'] = $student->id;
        HeightWeight::create($heightWeightData);

        // Bersihkan session setelah disimpan ke database
        $request->session()->forget(['parent_data', 'guardian_data']);

        // Redirect dengan notifikasi
        if ($student) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Siswa Berhasil Disimpan';
            return redirect()->route('student_data.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Siswa Gagal Disimpan';
            return redirect()->route('student_data.create')->withInput()->with($notification);
        }
    }


    // Mengupdate data siswa
    public function edit(string $id)
    {
        $student = Student::with(['latestHeightWeight', 'guardian', 'class'])->findOrFail($id);
        $guardians = Guardian::all();
        $classes = StudentClass::all();

        return view('student_data.edit', compact('student', 'guardians', 'classes'));
    }

    // Mengupdate data siswa
    public function update(Request $request, string $id)
    {
        $student = Student::with('latestHeightWeight', 'guardian')->findOrFail($id);

        $validated = $request->validate([
            'student_photo' => 'nullable|image',
            'status' => 'required|boolean',
            'nis' => 'required|numeric|digits_between:1,11|unique:students,nis,' . $student->id,
            'nisn' => 'required|numeric|digits_between:1,11|unique:students,nisn,' . $student->id,
            'nipd' => 'required|numeric|digits_between:1,10|unique:students,nipd,' . $student->id,
            'class_id' => 'required|exists:classes,id',
            'student_name' => 'required|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|numeric|digits_between:1,17|unique:students,nik,' . $student->id,
            'place_of_birth' => 'required|max:50',
            'date_of_birth' => 'required|date',
            'religion' => 'required|max:10',
            'address' => 'required|max:255',
            'special_needs' => 'nullable',
            'previous_school' => 'nullable|max:255',
            'birth_certificate_number' => 'nullable|max:60',
            'residence_type' => 'nullable|max:25',
            'no_kk' => 'required|numeric|digits_between:1,17|unique:students,no_kk,' . $student->id,
            'child_number' => 'nullable|numeric|digits_between:1,2',
            'number_of_siblings' => 'nullable|numeric|digits_between:1,2',
            'transportation' => 'nullable|max:20',
            'distance_to_school' => 'nullable|numeric|digits_between:1,2',
        ]);

        // Simpan foto siswa jika ada
        if ($request->hasFile('student_photo')) {
            // Hapus foto lama jika ada
            if ($student->student_photo) {
                Storage::delete('public/photos/' . $student->student_photo);
            }

            $path = $request->file('student_photo')->storeAs(
                'public/photos',
                'student_photo_' . time() . '.' . $request->file('student_photo')->extension()
            );
            $validated['student_photo'] = basename($path);
        }

        // Update data siswa
        $student->update($validated);

        // Redirect dengan notifikasi
        if ($student) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Siswa Berhasil Diperbarui';
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Siswa Gagal Diperbarui';
        }

        return redirect()->route('student_data.index')->with($notification);
    }

    // Menghapus data siswa
    public function destroy(Student $student)
    {
        // Hapus foto siswa jika ada
        if ($student->student_photo) {
            Storage::delete('public/photos/' . $student->student_photo);
        }

        // Hapus data siswa dan terkait melalui model events
        $student->delete();

        // Redirect dengan notifikasi
        $notification = [
            'alert-type' => 'success',
            'message' => 'Data Siswa Berhasil Dihapus'
        ];

        return redirect()->route('student_data.index')->with($notification);
    }
    // Import data siswa dari Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new StudentsDataImport, $request->file('file'));

        // Redirect dengan notifikasi
        $notification['alert-type'] = 'success';
        $notification['message'] = 'Data Siswa Berhasil Diimport';

        return redirect()->route('student_data.index')->with($notification);
    }
}
