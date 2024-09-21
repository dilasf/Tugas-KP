<?php

// namespace App\Imports;

// use App\Models\Teacher;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;


// class TeachersImport implements WithHeadingRow,ToModel
// {
//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */
//     public function model(array $row)
//     {

//         return new Teacher([
//             'teacher_name'  => $row['nama'],
//             'nuptk' => $row['nuptk'],
//             'gender' => $row['jenis_kelamin'],
//             'placeOfbirthy' => $row['tempat_lahir'],
//             'dateOfbirth' => $row['tanggal_lahir'],
//             'religion' => $row['agama'],
//             'address' => $row['alamat'],
//             'mobile_phone' => $row['no_hp'],
//             'nip' => $row['nip'],
//             'employment_status' => $row['status_kepegawaian'],
//             'typesOfCAR' => $row['jenis_ptk'],
//             'prefix' => $row['gelar_depan'],
//             'suffix' => $row['gelar_belakang'],
//             'education_Level' => $row['jenjang'],
//             'fieldOfStudy' => $row['jurusan_prodi'],
//             'certification' => $row['sertifikasi'],
//             'startDateofEmployment' => $row['tmt_kerja'],
//             'additional_Duties' => $row['tugas_tambahan'],
//             'teaching' => $row['mengajar'],
//             'competency' => $row['kompetensi'],
//             'mail' => $row['email'],
//         ]);
//     }
// }

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class TeachersImport implements WithHeadingRow, ToModel
{
    public function model(array $row)
    {
        // Temukan admin yang sudah ada berdasarkan NUPTK
        $existingAdmin = User::where('nuptk', $row['nuptk'])->first();

        if ($existingAdmin) {
            // Update data admin yang sudah ada
            $existingAdmin->update([
                'name' => $row['nama'],
                'nip' => $row['nip'],
                // Tambahkan field lain yang perlu diupdate
            ]);

            // Jika perlu, juga update data Teacher terkait
            $existingTeacher = Teacher::where('nuptk', $row['nuptk'])->first();
            if ($existingTeacher) {
                $existingTeacher->update([
                    'teacher_name' => $row['nama'],
                    'nip' => $row['nip'],
                    // Tambahkan field lain yang perlu diupdate
                ]);
            }

            // Tambahkan penugasan peran jika perlu
            $this->assignRoleAdmin($existingAdmin);

            return $existingTeacher;
        }

        // Jika bukan admin atau admin baru, lanjutkan dengan membuat atau memperbarui User dan Teacher
        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['nama'],
                'password' => Hash::make('password123'),
                'nuptk' => $row['nuptk'],
                // 'nip' => $row['nip'],
            ]
        );

        // Find or create associated teacher record
        $teacher = Teacher::updateOrCreate(
            ['nuptk' => $row['nuptk']],
            [
                'teacher_name' => $row['nama'],
                'gender' => $row['jenis_kelamin'],
                'placeOfbirth' => $row['tempat_lahir'],
                'dateOfbirth' => $row['tanggal_lahir'],
                'religion' => $row['agama'],
                'address' => $row['alamat'],
                'mobile_phone' => $row['no_hp'],
                'nip' => $row['nip'],
                'employment_status' => $row['status_kepegawaian'],
                'typesOfCAR' => $row['jenis_ptk'],
                'prefix' => $row['gelar_depan'],
                'suffix' => $row['gelar_belakang'],
                'education_Level' => $row['jenjang'],
                'fieldOfStudy' => $row['jurusan_prodi'],
                'certification' => $row['sertifikasi'],
                'startDateofEmployment' => $row['tmt_kerja'],
                'additional_Duties' => $row['tugas_tambahan'],
                'teaching' => $row['mengajar'],
                'competency' => $row['kompetensi'],
                'mail' => $row['email'],
            ]
        );

        // Associate teacher with user
        $user->teacher_id = $teacher->id;
        $user->save();

        // Assign role based on jenis_ptk
        $this->assignRoleByJenisPtk($user, $row['jenis_ptk']);

        return $teacher;
    }

    /**
     * Assign role 'admin' to the given user.
     */
    private function assignRoleAdmin($user)
    {
        $roleAdmin = Role::where('name', 'admin')->first();
        if ($roleAdmin) {
            $user->assignRole($roleAdmin);
        }
    }

    /**
     * Assign role based on jenis_ptk value.
     */
    private function assignRoleByJenisPtk($user, $jenisPtk)
    {
        switch ($jenisPtk) {
            case 'Kepala Sekolah':
                $user->role_id = Role::where('name', 'kepala_sekolah')->value('id');
                $user->assignRole('kepala_sekolah');
                break;
            case 'Guru Mapel':
                $user->role_id = Role::where('name', 'guru_mapel')->value('id');
                $user->assignRole('guru_mapel');
                break;
            case 'Guru Kelas':
                $user->role_id = Role::where('name', 'guru_kelas')->value('id');
                $user->assignRole('guru_kelas');
                break;
            default:
                $user->role_id = Role::where('name', 'siswa')->value('id');
                $user->assignRole('siswa');
                break;
        }
        $user->save();
    }
}
