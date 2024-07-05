<?php

namespace App\Imports;

use App\Models\Guardian;
use App\Models\HeightWeight;
use App\Models\Student;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentsDataImport implements ToModel, WithHeadingRow
{
    private function transformDate($value, $format = 'Y-m-d')
    {
        try {
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
                return Carbon::createFromFormat('d/m/Y', $value)->format($format);
            }
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format($format);
            }
            return Carbon::parse($value)->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function model(array $row)
    {
        // dd($row);
        // Ensure 'nis' is not empty
        if (!empty($row['nis'])) {
            // Import guardian data
            $guardianData = [
                'father_name' => $row['nama_ayah'] ?? null,
                'mother_name' => $row['nama_ibu'] ?? null,
                'father_nik' => $row['nik_ayah'] ?? null,
                'mother_nik' => $row['nik_ibu'] ?? null,
                'father_birth_year' => $this->transformDate($row['kelahiran_ayah'] ?? null),
                'mother_birth_year' => $this->transformDate($row['kelahiran_ibu'] ?? null),
                'father_education' => $row['pendidikan_ayah'] ?? null,
                'mother_education' => $row['pendidikan_ibu'] ?? null,
                'father_occupation' => $row['pekerjaan_ayah'] ?? null,
                'mother_occupation' => $row['pekerjaan_ibu'] ?? null,
                'father_income' => $row['penghasilan_ayah'] ?? null,
                'mother_income' => $row['penghasilan_ibu'] ?? null,
                'parent_phone_number' => $row['no_hp_ortu'] ?? null,
                'parent_email' => $row['email_ortu'] ?? null,
                'guardian_name' => $row['nama_wali'] ?? null,
                'guardian_nik' => $row['nik_wali'] ?? null,
                'guardian_birth_year' => $this->transformDate($row['kelahiran_wali'] ?? null),
                'guardian_education' => $row['pendidikan_wali'] ?? null,
                'guardian_occupation' => $row['pekerjaan_wali'] ?? null,
                'guardian_income' => $row['penghasilan_wali'] ?? null,
                'guardian_phone_number' => $row['no_hp_wali'] ?? null,
                'guardian_email' => $row['email_wali'] ?? null,
            ];

            // Create guardian if data is not null
            $guardianId = null;
            if (array_filter($guardianData)) {
                $guardian = new Guardian($guardianData);
                $guardian->save();
                $guardianId = $guardian->id;
            }

            $specialNeedsValue = $row['kebutuhan_khusus'] === 'Tidak' ? 0 : 1;

            // Create student record
            $student = new Student([
                'nis' => $row['nis'],
                'nisn' => $row['nisn'],
                'nipd' => $row['nipd'],
                'class_id' => $row['kelas'],
                'student_name' => $row['nama'],
                'gender' => $row['jk'],
                'nik' => $row['nik'],
                'place_of_birth' => $row['tempat_lahir'],
                'date_of_birth' => $this->transformDate($row['tgl_lahir']),
                'religion' => $row['agama'],
                'address' => $row['alamat'],
                'special_needs' => $specialNeedsValue,
                'previous_school' => $row['sekolah_asal'],
                'birth_certificate_number' => $row['akta'],
                'residence_type' => $row['jenis_tinggal'],
                'no_kk' => $row['no_kk'],
                'child_number' => $row['anak_ke'],
                'number_of_siblings' => $row['jml_saudara'],
                'transportation' => $row['transportasi'],
                'distance_to_school' => $row['jarak_sekolah'],
                'guardian_id' => $guardianId,
            ]);
            $student->save();

            // Create heightWeight record with the student_id
            $heightWeight = new HeightWeight([
                'student_id' => $student->id,
                'height' => $row['tinggi'] ?? null,
                'weight' => $row['berat'] ?? null,
                'head_size' => $row['ukuran_kepala'] ?? null,
            ]);
            $heightWeight->save();

            return $student;
        }
    }
}
