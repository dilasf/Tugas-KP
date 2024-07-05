<?php
namespace App\Http\Controllers;

use App\Imports\TeachersImport;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class TeacherController extends Controller
{
    public function index()
    {
        $data = Teacher::all();
        $sidebarOpen = false;
        return view('teacher_data.index', ['teachers' => $data], compact('sidebarOpen'));
    }

    public function create()
    {
        return view('teacher_data.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'nullable|image',
            'nuptk' => 'nullable|unique:teachers|numeric|digits_between:1,20',
            'teacher_name' => 'required|max:255',
            'placeOfbirth' => 'required|max:100',
            'dateOfbirth' => 'required|date',
            'gender' => 'required|max:12',
            'religion' => 'required|max:12',
            'address' => 'required|max:255',
            'mail' => 'required|email|unique:teachers|max:50',
            'mobile_phone' => 'nullable|unique:teachers|numeric|digits_between:1,13',
            'nip'=> 'nullable|unique:teachers|numeric|digits_between:1,20',
            'employment_status'=> 'required|max:50',
            'typesOfCAR'=> 'required|max:50',
            'prefix'=> 'nullable|max:30',
            'suffix'=> 'nullable|max:20',
            'education_Level'=> 'required|max:50',
            'fieldOfStudy'=> 'required|max:100',
            'certification'=> 'nullable|max:100',
            'startDateofEmployment'=> 'required|date',
            'additional_Duties'=> 'nullable|max:100',
            'teaching'=> 'nullable|max:150',
            'competency'=> 'nullable|max:100',
            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->storeAs(
                'public/photos',
                'photo_' . time() . '.' . $request->file('photo')->extension()
            );
            $validated['photo'] = basename($path);
        }

        $teacher = Teacher::create($validated);

        // Membuat user terkait
        $user = User::create([
            'teacher_id' => $teacher->id,
            'name' => $validated['teacher_name'],
            'email' => $validated['mail'],
            'nuptk' => $validated['nuptk'],
            'nip' => $validated['nip'],
            'password' => Hash::make('password123'),
        ]);

        // Menetapkan peran berdasarkan jenis_ptk
        $this->assignRoleByJenisPtk($user, $validated['typesOfCAR']);

        if ($teacher) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Guru dan Akun Berhasil Disimpan';
            return redirect()->route('teacher_data.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Guru dan Akun Gagal Disimpan';
            return redirect()->route('teacher_data.create')->withInput()->with($notification);
        }
    }

    public function edit(string $id)
    {
        $data['teachers'] = Teacher::find($id);
        return view('teacher_data.edit', $data);
    }

    public function update(Request $request, string $id)
{
    $teacher = Teacher::findOrFail($id);
    $validated = $request->validate([
        'photo' => 'nullable|image',
        'nuptk' => 'nullable|numeric|unique:teachers,nuptk,' . $id . '|digits_between:1,20',
        'teacher_name' => 'nullable|max:255',
        'placeOfbirth' => 'required|max:100',
        'dateOfbirth' => 'required|date',
        'gender' => 'required|max:12',
        'religion' => 'required|max:10',
        'address' => 'required|max:255',
        'mail' => 'required|email|unique:teachers,mail,'.$id,
        'mobile_phone' => 'nullable|numeric|unique:teachers,mobile_phone,' . $id,
        'nip'=> 'nullable|numeric|unique:teachers,nip,' . $id . '|digits_between:1,20',
        'employment_status'=> 'required|max:50',
        'typesOfCAR'=> 'required|max:50',
        'prefix'=> 'nullable|max:30',
        'suffix'=> 'nullable|max:20',
        'education_Level'=> 'required|max:50',
        'fieldOfStudy'=> 'required|max:100',
        'certification'=> 'nullable|max:100',
        'startDateofEmployment'=> 'required|date',
        'additional_Duties'=> 'nullable|max:100',
        'teaching'=> 'nullable|max:150',
        'competency'=> 'nullable|max:100',
        'status' => 'required|boolean',
    ]);

    if ($request->hasFile('photo')) {
        if ($teacher->photo != null) {
            // Menghapus foto lama dari penyimpanan jika ada
            Storage::delete('public/photos/' . $teacher->photo);
        }

        $path = $request->file('photo')->storeAs(
            'public/photos',
            'photo_' . time() . '.' . $request->file('photo')->extension()
        );
        $validated['photo'] = basename($path);
    }

    $teacher->update($validated);

    // Update user terkait
    $user = User::where('teacher_id', $teacher->id)->first();
    if ($user) {
        $user->update([
            'name' => $validated['teacher_name'],
            'email' => $validated['mail'],
            'nuptk' => $validated['nuptk'],
            'nip' => $validated['nip'],
            'status' => $validated['status'] ? 'active' : 'inactive', // Status akun
        ]);

        // Menetapkan peran berdasarkan jenis_ptk
        $this->assignRoleByJenisPtk($user, $validated['typesOfCAR']);
    }

    if ($teacher) {
        $notification['alert-type'] = 'success';
        $notification['message'] = 'Data Guru dan Akun Berhasil Diperbaharui';
        return redirect()->route('teacher_data.index')->with($notification);
    } else {
        $notification['alert-type'] = 'error';
        $notification['message'] = 'Data Guru dan Akun Gagal Diperbaharui';
        return redirect()->route('teacher_data.edit', $id)->withInput()->with($notification);
    }
}


    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $photoPath = 'public/photos/' . $teacher->photo;

        if (Storage::exists($photoPath)) {
            Storage::delete($photoPath);
        }

        // Hapus user terkait
        $userDeleted = $teacher->user()->delete();

        // Hapus teacher setelah user terhapus
        $teacherDeleted = $teacher->delete();

        if ($userDeleted && $teacherDeleted) {
            $notification['alert-type'] = 'success';
            $notification['message'] = 'Data Guru dan Akun Berhasil Dihapus';
            return redirect()->route('teacher_data.index')->with($notification);
        } else {
            $notification['alert-type'] = 'error';
            $notification['message'] = 'Data Guru dan Akun Gagal Dihapus';
            return redirect()->route('teacher_data.index')->with($notification);
        }
    }

    public function import(Request $req)
    {
        $req->validate([
            'file' => 'required|max:10000|mimes:xlsx,xls',
        ]);

        Excel::import(new TeachersImport, $req->file('file'));

        $notification = array(
            'message' => 'Import data berhasil dilakukan',
            'alert-type' => 'success'
        );

        return redirect()->route('teacher_data.index')->with($notification);
    }

    private function assignRoleByJenisPtk($user, $jenisPtk)
    {
        switch ($jenisPtk) {
            case 'Kepala Sekolah':
                $role = Role::firstOrCreate(['name' => 'kepala_sekolah']);
                $user->assignRole($role);
                break;
            case 'Guru Mapel':
                $role = Role::firstOrCreate(['name' => 'guru_mapel']);
                $user->assignRole($role);
                break;
            case 'Guru Kelas':
                $role = Role::firstOrCreate(['name' => 'guru_kelas']);
                $user->assignRole($role);
                break;
            default:
                $role = Role::firstOrCreate(['name' => 'siswa']);
                $user->assignRole($role);
                break;
        }
    }
}
