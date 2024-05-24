<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class TeachersImport implements WithHeadingRow,ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

            // return new Teacher([
            //     'teacher_name'  => $row['nama'],
            //     // tambahkan properti lainnya jika ada
            // ]);

        return new Teacher([
            'teacher_name'  => $row['nama'],
            'nuptk' => $row['nuptk'],
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
        ]);
    }
}
