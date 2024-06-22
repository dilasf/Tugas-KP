<?php

namespace App\Http\Controllers;

use App\Imports\StudentsDataImport;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\HeightWeight;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['class', 'guardian', 'latestHeightWeight'])->get();

        $sidebarOpen = false;
        return view('student_data.index', compact('students','sidebarOpen'), ['guardians' => $students]);
    }

    //detail informasi siswa
    public function show($id)
{
    $student = Student::with(['guardian'])->findOrFail($id);

    $latestHeight = $student->latestNonNullHeight();
    $latestWeight = $student->latestNonNullWeight();
    $latestHeadSize = $student->latestNonNullHeadSize();

    return view('student_data.show-detail', compact('student', 'latestHeight', 'latestWeight', 'latestHeadSize'));
}


    //Data Orang Tua
    public function createParent()
    {
        return view('student_data.create-parent');
    }

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
        session(['parent_data' => $validated]);

        return redirect()->route('student_data.create');
    }

    //Data Wali
    public function createGuardian()
    {
        return view('student_data.create-guardian');
    }

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
        session(['guardian_data' => $validated]);

        return redirect()->route('student_data.create');
    }

        //Data Siswa
    public function create()
    {
        $height_weights = HeightWeight::all();
        $guardians = Guardian::all();
        $classes = StudentClass::all();

        return view('student_data.create', compact('height_weights', 'guardians', 'classes'));
    }

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

    if ($request->hasFile('student_photo')) {
        $path = $request->file('student_photo')->storeAs(
            'public/photos',
            'student_photo_' . time() . '.' . $request->file('student_photo')->extension()
        );
        $validated['student_photo'] = basename($path);
    }

    $heightWeightData = $request->validate([
        'height' => 'nullable|integer',
        'weight' => 'nullable|integer',
        'head_size' => 'nullable|integer',
    ]);

    $parentData = session('parent_data', []);
    $guardianData = session('guardian_data', []);

    if (empty($parentData) && empty($guardianData)) {
        return redirect()->back()->withInput()->withErrors(['message' => 'Please provide either parent or guardian data.']);
    }

    // Merge parent and guardian data, and ensure there are no duplicate unique fields
    $mergedData = array_merge([
        'father_name' => null,
        'mother_name' => null,
        'father_nik' => null,
        'mother_nik' => null,
        'father_birth_year' => null,
        'mother_birth_year' => null,
        'father_education' => null,
        'mother_education' => null,
        'father_occupation' => null,
        'mother_occupation' => null,
        'father_income' => null,
        'mother_income' => null,
        'parent_phone_number' => null,
        'parent_email' => null,
        'guardian_name' => null,
        'guardian_nik' => null,
        'guardian_birth_year' => null,
        'guardian_education' => null,
        'guardian_occupation' => null,
        'guardian_income' => null,
        'guardian_phone_number' => null,
        'guardian_email' => null,
    ], $parentData, $guardianData);

    // Remove duplicates from merged data
    $uniqueFields = ['guardian_nik', 'parent_phone_number', 'parent_email'];
    foreach ($uniqueFields as $field) {
        $values = array_filter([$parentData[$field] ?? null, $guardianData[$field] ?? null]);
        if (count($values) > 1) {
            return redirect()->back()->withInput()->withErrors(['message' => "Duplicate value for unique field: $field"]);
        }
        $mergedData[$field] = array_pop($values);
    }

    $guardian = Guardian::create($mergedData);

    $student = Student::create(array_merge($validated, [
        'guardian_id' => $guardian->id,
    ]));

    $heightWeightData['student_id'] = $student->id;
    HeightWeight::create($heightWeightData);

    if ($student) {
        session()->forget(['parent_data', 'guardian_data']);

        $notification['alert-type'] = 'success';
        $notification['message'] = 'Data Siswa Berhasil Disimpan';
        return redirect()->route('student_data.index')->with($notification);
    } else {
        $notification['alert-type'] = 'error';
        $notification['message'] = 'Data Siswa Gagal Disimpan';
        return redirect()->route('student_data.create')->withInput()->with($notification);
    }
}


    // epdate data sisea
    public function edit(string $id)
    {
        $student = Student::with(['latestHeightWeight', 'guardian', 'class'])->findOrFail($id);
        $guardians = Guardian::all();
        $classes = StudentClass::all();

        return view('student_data.edit', compact('student', 'guardians', 'classes'));
    }


    public function update(Request $request, string $id)
    {
        $student = Student::with('latestHeightWeight', 'guardian')->findOrFail($id);

        // Validasi data siswa
        $validated = $request->validate([
            'student_photo' => 'nullable|image',
            'status' => 'required|boolean',
            'nis' => 'required|numeric|digits_between:1,11,' . $student->id,
            'nisn' => 'required|numeric|digits_between:1,11,' . $student->id,
            'nipd' => 'required|numeric|digits_between:1,10,' . $student->id,
            'class_id' => 'required|exists:classes,id',
            'student_name' => 'required|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|numeric|digits_between:1,17,' . $student->id,
            'place_of_birth' => 'required|max:50',
            'date_of_birth' => 'required|date',
            'religion' => 'required|max:10',
            'address' => 'required|max:255',
            'special_needs' => 'nullable',
            'previous_school' => 'nullable|max:255',
            'birth_certificate_number' => 'nullable|max:60',
            'residence_type' => 'nullable|max:25',
            'no_kk' => 'required|numeric|digits_between:1,17,' . $student->id,
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

        // Validasi data berat badan
        $heightWeightData = $request->validate([
            'height' => 'nullable|integer',
            'weight' => 'nullable|integer',
            'head_size' => 'nullable|integer',
        ]);

        // Validasi data wali/orang tua
        $guardianData = $request->validate([
            'father_name' => 'nullable|max:255',
            'mother_name' => 'nullable|max:255',
            'father_nik' => 'nullable|numeric|digits_between:1,17,' . ($student->guardian->id ?? 'null'),
            'mother_nik' => 'nullable|numeric|digits_between:1,17,' . ($student->guardian->id ?? 'null'),
            'father_birth_year' => 'nullable|date',
            'mother_birth_year' => 'nullable|date',
            'father_education' => 'nullable|max:255',
            'mother_education' => 'nullable|max:255',
            'father_occupation' => 'nullable|max:255',
            'mother_occupation' => 'nullable|max:255',
            'father_income' => 'nullable|numeric',
            'mother_income' => 'nullable|numeric',
            'parent_phone_number' => 'nullable|numeric|digits_between:1,17,' . ($student->guardian->id ?? 'null'),
            'parent_email' => 'nullable|email|max:255,' . ($student->guardian->id ?? 'null'),
            'guardian_name' => 'nullable|max:255',
            'guardian_nik' => 'nullable|numeric|digits_between:1,17,' . ($student->guardian->id ?? 'null'),
            'guardian_birth_year' => 'nullable|date',
            'guardian_education' => 'nullable|max:255',
            'guardian_occupation' => 'nullable|max:255',
            'guardian_income' => 'nullable|numeric',
            'guardian_phone_number' => 'nullable|numeric|digits_between:1,17,' . ($student->guardian->id ?? 'null'),
            'guardian_email' => 'nullable|email|max:255,' . ($student->guardian->id ?? 'null'),
        ]);

        // Perbarui data siswa
        $student->update($validated);

        // Perbarui data berat badan
        if ($student->latestHeightWeight) {
            $student->latestHeightWeight->update($heightWeightData);
        } else {
            // Create a new HeightWeight record if it doesn't exist
            $heightWeightData['student_id'] = $student->id;
            HeightWeight::create($heightWeightData);
        }

        // Perbarui atau buat data wali/orang tua
        if ($student->guardian) {
            $student->guardian->update($guardianData);
        } else {
            $guardian = Guardian::create($guardianData);
            $student->guardian()->associate($guardian);
            $student->save();
        }

        // Pengaturan notifikasi berdasarkan hasil pembaruan
        if ($student) {
            session()->forget(['parent_data', 'guardian_data']);

            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Siswa Berhasil Diperbaharui';
            return redirect()->route('student_data.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Siswa Gagal Diperbaharui';
            return redirect()->route('student_data.edit', ['id' => $id])->withInput()->with($notification);
        }
    }


    //Updated data orang tua
    public function editParent($id)
    {
        $student = Student::findOrFail($id);
        $guardian = $student->guardian;

        return view('student_data.edit-parent', compact('student', 'guardian'));
    }

    public function updateParent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $guardian = $student->guardian ?? new Guardian();

        $request->validate([
            'father_name' => 'nullable|max:255',
            'mother_name' => 'nullable|max:255',
            'father_nik' => 'nullable|numeric|digits_between:1,17,' . ($guardian->id ?? 'null'),
            'mother_nik' => 'nullable|numeric|digits_between:1,17,' . ($guardian->id ?? 'null'),
            'father_birth_year' => 'nullable|date',
            'mother_birth_year' => 'nullable|date',
            'father_education' => 'nullable|max:255',
            'mother_education' => 'nullable|max:255',
            'father_occupation' => 'nullable|max:255',
            'mother_occupation' => 'nullable|max:255',
            'father_income' => 'nullable|max:200',
            'mother_income' => 'nullable|max:200',
            'parent_phone_number' => 'nullable|numeric|digits_between:1,17,' . ($guardian->id ?? 'null'),
            'parent_email' => 'nullable|email|max:255,' . ($guardian->id ?? 'null'),
        ]);

        $guardian->fill([
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'father_nik' => $request->father_nik,
            'mother_nik' => $request->mother_nik,
            'father_birth_year' => $request->father_birth_year,
            'mother_birth_year' => $request->mother_birth_year,
            'father_education' => $request->father_education,
            'mother_education' => $request->mother_education,
            'father_occupation' => $request->father_occupation,
            'mother_occupation' => $request->mother_occupation,
            'father_income' => $request->father_income,
            'mother_income' => $request->mother_income,
            'parent_phone_number' => $request->parent_phone_number,
            'parent_email' => $request->parent_email,
        ])->save();

        $student->guardian()->associate($guardian);
        $student->save();

        return redirect()->route('student_data.edit', ['id' => $id]);
    }

    //Updated Data Wali
    public function editGuardian($id)
    {
        $student = Student::findOrFail($id);
        $guardian = $student->guardian;

        return view('student_data.edit-guardian', compact('student', 'guardian'));
    }

    public function updateGuardian(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $guardian = $student->guardian;

        $request->validate([
            'guardian_name' => 'nullable|string|max:255',
            'guardian_nik' => 'nullable|numeric,'.$guardian->id,
            'guardian_birth_year' => 'nullable|date',
            'guardian_education' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_income' => 'nullable|max:200',
            'guardian_phone_number' => 'nullable|numeric,'.$guardian->id,
            'guardian_email' => 'nullable|email,'.$guardian->id.'|max:255',
        ]);

        $guardian->fill([
            'guardian_name' => $request->guardian_name,
            'guardian_nik' => $request->guardian_nik,
            'guardian_birth_year' => $request->guardian_birth_year,
            'guardian_education' => $request->guardian_education,
            'guardian_occupation' => $request->guardian_occupation,
            'guardian_income' => $request->guardian_income,
            'guardian_phone_number' => $request->guardian_phone_number,
            'guardian_email' => $request->guardian_email,
        ])->save();

            $student->guardian()->associate($guardian);
            $student->save();
        return redirect()->route('student_data.edit', ['id' => $id]);
    }

        public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $photoPath = 'public/photos/' . $student->student_photo;

        if (Storage::exists($photoPath)) {
            Storage::delete($photoPath);
        }

        if ($student->guardian) {
            $student->guardian->delete();
        }

        if ($student->heightWeight) {
            $student->heightWeight->delete();
        }

        $data = $student->delete();

        if ($data) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Siswa Berhasil Dihapus';
            return redirect()->route('student_data.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Siswa Gagal Dihapus';
            return redirect()->route('student_data.index')->with($notification);
        }
    }

    public function import(Request $req) {
        $req->validate([
            'file' => 'required|max:10000|mimes:xlsx,xls',
        ]);


        Excel::import(new StudentsDataImport, $req->file('file'));

        $notification = array(
            'message' => 'Import data berhasil dilakukan',
            'alert-type' => 'success'
        );
        return redirect()->route('student_data.index')->with($notification);
    }


}
