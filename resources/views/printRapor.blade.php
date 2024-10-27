<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Siswa</title>
    <style>
        body {
            line-height: 1.5;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .font-semibold {
            font-weight: 600;
        }
        .font-medium {
            font-weight: 500;
        }
        .text-xl {
            font-size: 1rem;
        }
        .bg-white {
            background-color: #ffffff;
        }
        .shadow-xl {
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .p-6 {
            padding: 1.5rem;
        }
        .py-10 {
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }

        /* Table Design */
        .table-data-diri, .table-competency {
            width: 100%;
            border-collapse: collapse;
        }
        /* .table-data-diri th, .table-data-diri td, */
        .table-competency th, .table-competency td {
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 0.9rem;
            text-align: justify;
        }
        .table-competency th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            font-size: 0.9rem;
            text-align: justify;
        }
        .colon {
            width: 20px;
            text-align: center;
        }
        .table-container {
            display: table;
            width: 100%;
        }
        .table-left, .table-right {
            display: table-cell;
            padding: 0 20px;
        }
        .table-right {
            text-align: right;
        }

        /* Styling untuk Saran Section */
        .saran-title {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .saran-box {
            margin-top: 10px;
            padding: 1.25rem;
            border: 1px solid black;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            text-align: justify;
        }
        .text-md {
            font-size: 14px;
        }
        .text-gray-800 {
            color: #4a4a4a;
        }
        .mt-10 {
            margin-top: 10px;
        }
        .mt-20 {
            margin-top: 20px;
        }
        .mt-8 {
            margin-top: 8px;
        }
        .px-8 {
            padding-left: 8px;
            padding-right: 8px;
        }
        .float-right {
            float: right;
        }
        .text-center {
            text-align: center;
        }
        .underline {
            text-decoration: underline;
        }
        .underline-offset-2 {
            text-underline-offset: 2px;
        }
        .border-dotted {
            border-style: dotted;
        }
        .w-1/8 {
            width: 12.5%;
        }
        .my-1 {
            margin-top: 1px;
            margin-bottom: 1px;
        }
        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }
        .flex {
            display: flex;
        }
        .justify-between {
            justify-content: space-between;
        }
        .items-center {
            align-items: center;
        }
    </style>

</head>
<body>
    @php
    $numberWords = [
        1 => 'Satu',
        2 => 'Dua',
        3 => 'Tiga',
        4 => 'Empat',
        5 => 'Lima',
        6 => 'Enam',
    ];

    $classLevel = optional($student->class)->level ?? 'N/A';
    $classLevelWord = is_numeric($classLevel) ? $numberWords[(int)$classLevel] ?? $classLevel : $classLevel;

    $selectedSemester = $semesters->firstWhere('id', $selectedSemesterYearId);
    $semesterNumber = $selectedSemester ? $selectedSemester->semester : 'N/A';
    $semesterWord = $numberWords[$semesterNumber] ?? 'N/A';
    @endphp

    <!-- Data Diri -->
    <div class="container bg-white shadow-xl rounded-lg p-6">
        <p class="font-semibold text-xl text-center mb-6">RAPOR DAN PROFIL PESERTA DIDIK</p>
        <div class="table-container">
            <div class="table-left">
                <table class="table-data-diri">
                    <tr>
                        <td class="label">Nama Peserta Didik</td>
                        <td class="colon">:</td>
                        <td class="value">{{ strtoupper($student->student_name ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIS</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $student->nis ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nama Sekolah</td>
                        <td class="colon">:</td>
                        <td class="value">{{ strtoupper($rapors->first()->school_name ?? 'SDN DAWUAN') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat Sekolah</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $rapors->first()->school_address ?? 'KP Pasir Eurih' }}</td>
                    </tr>
                </table>
            </div>
            <div class="table-right">
                <table class="table-data-diri">
                    <tr>
                        <td class="label">Kelas</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $classLevel }} ({{ $classLevelWord }})</td>
                    </tr>
                    <tr>
                        <td class="label">Semester</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $semesterNumber }} ({{ $semesterWord }})</td>
                    </tr>
                    <tr>
                        <td class="label">Tahun Ajaran</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $selectedSemester ? $selectedSemester->year : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- END Data Diri -->

    <!-- Kompetensi Sikap -->
    <h4 class="font-semibold text-xl mt-10 mb-4">A. Kompetensi Sikap</h4>
    <div class="flex flex-col overflow-hidden">
        <div class="-mx-6">
            <div class="inline-block min-w-full py-1 md:px-2 lg:px-10">
                <div class="overflow-hidden ring-1 ring-gray-300 ring-opacity-50">
                    <table class="table-competency">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-center">No</th>
                                <th class="px-4 py-2 text-center">Kompetensi</th>
                                <th class="px-4 py-2 text-center">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-2 text-center">1</td>
                                <td class="px-4 py-2">Sikap Spiritual</td>
                                <td class="px-4 py-2">{{ $rapors->first()->spiritual_attitude ?? 'Data tidak tersedia' }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-center">2</td>
                                <td class="px-4 py-2">Sikap Sosial</td>
                                <td class="px-4 py-2">{{ $rapors->first()->social_attitudes ?? 'Data tidak tersedia' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END Kompetensi Sikap -->

    <!-- Kompetensi Pengetahuan dan Keterampilan -->
    <h4 class="font-semibold text-xl mt-10 mb-4">B. Kompetensi Pengetahuan dan Keterampilan</h4>
    <div class="flex flex-col overflow-hidden">
        <div class="-mx-6">
            <div class="inline-block min-w-full py-1 md:px-2 lg:px-10">
                <div class="overflow-hidden ring-1 ring-gray-700 ring-opacity-5">
                    <table class="table-competency">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th rowspan="2" class="px-2 py-2 text-center">No</th>
                                <th rowspan="2" class="px-2 py-2 text-center">Mata Pelajaran</th>
                                <th colspan="3" class="px-2 py-2 text-center">Pengetahuan</th>
                                <th rowspan="2" class="px-2 py-2 text-center">KKM</th>
                                <th colspan="3" class="px-2 py-2 text-center">Keterampilan</th>
                            </tr>
                            <tr>
                                <th class="px-2 py-2 text-center">Nilai</th>
                                <th class="px-2 py-2 text-center">Predikat</th>
                                <th class="px-2 py-2 text-center">Deskripsi</th>
                                <th class="px-2 py-2 text-center">Nilai</th>
                                <th class="px-2 py-2 text-center">Predikat</th>
                                <th class="px-2 py-2 text-center">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                                @php $num = 1; @endphp
                            @foreach($rapors->groupBy('grade.classSubject.subject.id') as $subjectRapors)
                                @php
                                    $firstRapor = $subjectRapors->first();
                                    $subject = optional($firstRapor->grade->classSubject->subject)->subject_name ?? 'N/A';
                                    $averageKnowledgeScore = round($firstRapor->grade->average_knowledge_score) ?? '0';
                                    $gradeKnowledge = $firstRapor->grade->gradeKnowledge ?? '-';
                                    $descriptionKnowledge = $firstRapor->grade->descriptionKnowledge ?? 'N/A';

                                    $averageSkillScore = round($firstRapor->grade->average_skill_score) ?? '0';
                                    $gradeSkill = $firstRapor->grade->gradeSkill ?? '-';
                                    $descriptionSkill = $firstRapor->grade->descriptionSkill ?? 'N/A';
                                @endphp
                            <tr>
                                <td class="px-2 py-2 text-center">{{ $num++ }}</td>
                                <td class="px-2 py-2">{{ $subject }}</td>
                                <td class="px-2 py-2 text-center">{{ $averageKnowledgeScore }}</td>
                                <td class="px-2 py-2 text-center">{{ $gradeKnowledge }}</td>
                                <td class="px-2 py-2">{{ $descriptionKnowledge }}</td>
                                <td class="px-2 py-2 text-center">{{ $firstRapor->grade->kkm ?? 'N/A' }}</td>
                                <td class="px-2 py-2 text-center">{{ $averageSkillScore }}</td>
                                <td class="px-2 py-2 text-center">{{ $gradeSkill }}</td>
                                <td class="px-2 py-2">{{ $descriptionSkill }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END Kompetensi Pengetahuan dan Keterampilan -->

    <!-- Ekstrakulikuler -->
    <h4 class="font-semibold text-xl mt-10 mb-4">C. Ekstrakurikuler</h4>
    <div class="flex flex-col overflow-hidden">
        <div class="-mx-6">
            <div class="inline-block min-w-full py-1 md:px-2 lg:px-10">
                <div class="overflow-hidden ring-1 ring-gray-700 ring-opacity-5">
                    <table class="table-competency">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-2 py-2 text-center">No</th>
                                <th class="px-2 py-2 text-center">Kegiatan</th>
                                <th class="px-2 py-2 text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                                @php
                                    $num = 1;
                                    $hasExtracurricular = false;
                                @endphp
                                @foreach ($rapors as $rapor)
                                    @foreach ($rapor->extracurricular as $extracurricular)
                                        @php $hasExtracurricular = true; @endphp
                                <tr>
                                    <td class="px-2 py-2 text-center">{{ $num++ }}</td>
                                    <td class="px-2 py-2">{{ $extracurricular->activity }}</td>
                                    <td class="px-2 py-2">{{ $extracurricular->description }}</td>
                                </tr>
                                    @endforeach
                                @endforeach

                                @if (!$hasExtracurricular)
                                    @for ($i = $num; $i <= 3; $i++)
                                    <tr>
                                        <td class="px-2 py-2 text-center">{{ $i }}</td>
                                        <td colspan="3" class="px-2 py-2 text-center">-</td>
                                    </tr>
                                    @endfor
                                @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--END Ekstrakulikuler -->

    <!-- Saran Section -->
    <div class="mt-10">
        <h4 class="font-semibold text-xl mt-10 mb-4">D. Saran</h4>
            <div class="saran-box">
                {{ $rapors->first()->suggestion ?? 'Tidak ada saran yang tersedia' }}
            </div>
    </div>
    <!-- END Saran Section -->

    <!-- Tinggi Badan Section -->
    <h4 class="font-semibold text-xl mt-10 mb-4">E. Data Tinggi Berat Badan</h4>
    <div class="flex flex-col overflow-hidden">
        <div class="-mx-6">
            <div class="inline-block min-w-full py-1 md:px-2 lg:px-10">
                <div class="overflow-hidden ring-1 ring-gray-700 ring-opacity-5">
                    <table class="table-competency">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-2 py-2 text-center">No</th>
                                <th class="px-2 py-2 text-center">Aspek yang dinilai</th>
                                <th class="px-2 py-2 text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                                @php $num = 1; @endphp
                            <tr>
                                <td class="px-2 py-2 text-center">{{ $num++ }}</td>
                                <td class="px-2 py-2">Tinggi Badan</td>
                                <td class="px-2 py-2 text-center">
                                    @if ($student->heightWeights->isNotEmpty())
                                        @php
                                            $heightWeight = $student->heightWeights->firstWhere(function ($heightWeight) use ($selectedSemesterYearId) {
                                                return $heightWeight->rapor && $heightWeight->rapor->grade && $heightWeight->rapor->grade->semester_year_id == $selectedSemesterYearId;
                                            });
                                        @endphp

                                        @if ($heightWeight && $heightWeight->height !== null)
                                            {{ $heightWeight->height }} cm
                                        @else
                                            <span class="text-gray-400">Data tidak tersedia</span>
                                        @endif
                                    @else
                                            <span class="text-gray-400">Data tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-2 text-center">{{ $num++ }}</td>
                                <td class="px-2 py-2">Berat Badan</td>
                                <td class="px-2 py-2 text-center">
                                    @if ($student->heightWeights->isNotEmpty())
                                        @php
                                            $weightHeight = $student->heightWeights->firstWhere(function ($heightWeight) use ($selectedSemesterYearId) {
                                                return $heightWeight->rapor && $heightWeight->rapor->grade && $heightWeight->rapor->grade->semester_year_id == $selectedSemesterYearId;
                                            });
                                        @endphp

                                        @if ($weightHeight && $weightHeight->weight !== null)
                                            {{ $weightHeight->weight }} kg
                                        @else
                                            <span class="text-gray-400">Data tidak tersedia</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Data tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END Tinggi Badan Section -->

    <!-- Kesehatan Section -->
    <h4 class="font-semibold text-xl mt-10 mb-4">F. Kondisi Kesehatan</h4>
    <div class="flex flex-col overflow-hidden">
        <div class="-mx-6">
            <div class="inline-block min-w-full py-1 md:px-2 lg:px-10">
                <div class="overflow-hidden ring-1 ring-gray-700 ring-opacity-5">
                    <table class="table-competency">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-2 py-2 text-center">No</th>
                                <th class="px-2 py-2 text-center">Aspek Fisik</th>
                                <th class="px-2 py-2 text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                                @php $num = 1; @endphp
                            <tr>
                                <td class="px-2 py-2 text-center">{{ $num++ }}</td>
                                <td class="px-2 py-2">Pendengaran</td>
                                <td class="px-2 py-2 text-center">
                                    @if ($rapors->isNotEmpty() && $rapors[0]->health && $rapors[0]->health->hearing !== null)
                                        {{ $rapors[0]->health->hearing }}
                                    @else
                                        <span class="text-gray-400">Data tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="px-2 py-2 text-center">{{ $num++ }}</td>
                                <td class="px-2 py-2">Penglihatan</td>
                                <td class="px-2 py-2 text-center">
                                    @if ($rapors->isNotEmpty() && $rapors[0]->health && $rapors[0]->health->vision !== null)
                                        {{ $rapors[0]->health->vision }}
                                    @else
                                        <span class="text-gray-400">Data tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="px-2 py-2 text-center">{{ $num++ }}</td>
                                <td class="px-2 py-2">Gigi</td>
                                <td class="px-2 py-2 text-center">
                                    @if ($rapors->isNotEmpty() && $rapors[0]->health && $rapors[0]->health->tooth !== null)
                                        {{ $rapors[0]->health->tooth }}
                                    @else
                                        <span class="text-gray-400">Data tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END Kesehatan Section -->

    <!-- Persentasi Section -->
    <h4 class="font-semibold text-xl mt-10 mb-4">G. Prestasi</h4>
    <div class="flex flex-col overflow-hidden">
        <div class="-mx-6">
            <div class="inline-block min-w-full py-1 md:px-2 lg:px-10">
                <div class="overflow-hidden ring-1 ring-gray-700 ring-opacity-5">
                    <table class="table-competency">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-2 py-2 text-center">No</th>
                                <th class="px-2 py-2 text-center">Jenis Prestasi</th>
                                <th class="px-2 py-2 text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php $num = 1; @endphp
                            @php $hasAchievements = false; @endphp

                            @foreach ($rapors as $rapor)
                                @foreach ($rapor->achievement ?? [] as $achievement)
                                    <tr>
                                        <td class="px-2 py-2 text-center">{{ $num++ }}</td>
                                        <td class="px-2 py-2">{{ $achievement->achievement_type }}</td>
                                        <td class="px-2 py-2">{{ $achievement->description }}</td>
                                    </tr>
                                    @php $hasAchievements = true; @endphp
                                @endforeach
                            @endforeach

                            @if (!$hasAchievements)
                                @for ($i = $num; $i <= 3; $i++)
                                    <tr>
                                        <td class="px-2 py-2 text-center">{{ $i }}</td>
                                        <td colspan="3" class="px-2 py-2 text-center">-</td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
     <!-- END Persentasi Section -->

     <!-- Tabel Kehadiran Section -->
    <h3 class="font-semibold text-xl mt-10 mb-4">H. Ketidakhadiran</h3>
    <div class="flex flex-col overflow-hidden">
        <div class="-mx-6">
            <div class="inline-block min-w-full py-1 md:px-2 lg:px-10">
                <div class="overflow-hidden ring-1 ring-gray-700 ring-opacity-5">
                    <table class="table-competency">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-2 py-2 text-center">No</th>
                                <th class="px-2 py-2 text-center">Keterangan</th>
                                <th class="px-2 py-2 text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr>
                                <td class="px-2 py-2 text-center">1</td>
                                <td class="px-2 py-2">Sakit</td>
                                <td class="px-2 py-2 text-center">{{ $totalSick }} hari</td>
                            </tr>
                            <tr>
                                <td class="px-2 py-2 text-center">2</td>
                                <td class="px-2 py-2">Ijin</td>
                                <td class="px-2 py-2 text-center">{{ $totalPermission }} hari</td>
                            </tr>
                            <tr>
                                <td class="px-2 py-2 text-center">3</td>
                                <td class="px-2 py-2">Tanpa Keterangan</td>
                                <td class="px-2 py-2 text-center">{{ $totalUnexcused }} hari</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
     <!-- END Persentasi Section -->

     <!-- tgl cetak Section -->
    <div class="text-md text-gray-800 mt-10 px-8 float-right">
        Cianjur,
        @if ($rapors->isEmpty())
            Belum Dicetak
        @else
            {{ $rapors->first()->print_date ? \Carbon\Carbon::parse($rapors->first()->print_date)->locale('id')->isoFormat('DD MMMM YYYY') : 'Belum Dicetak' }}
        @endif
    </div>

    <!-- Tanda Tangan Section -->
    <div class="container mt-20">
        <table class="table-data-diri" style="width: 100%; text-align: center; margin-top: 10px;">
            <tr>
                <!-- Orang Tua/Wali Column -->
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p style="margin-bottom: 70px;">Orang Tua/Wali</p>
                    <p>....................................</p>
                </td>

                <!-- Guru Kelas Column -->
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p style="margin-bottom: 70px;">Guru Kelas</p>
                    @php
                        $classTeacher = $student->class->teacher;
                    @endphp
                    <p class="font-semibold underline">
                        {{ ucwords(strtolower($classTeacher->prefix ?? '')) }}
                        {{ ucwords(strtolower($classTeacher->teacher_name ?? '')) }}
                        {{ ucwords(strtolower($classTeacher->suffix ?? '')) }}
                    </p>
                    <p>NIP: {{ $classTeacher->nip ?? '-' }}</p>
                </td>
        </tr>
    </table>

        <!-- Kepala Sekolah Section -->
        <div class="text-center" style="margin-top: 20px;">
            <p>Mengetahui,</p>
            <p style="margin-bottom: 60px;">Kepala Sekolah</p>
            @if ($headmaster && $headmaster->typesOfCAR === 'Kepala Sekolah')
                <p class="font-semibold underline">
                    {{ ucwords(strtolower($headmaster->prefix ?? '')) }}
                    {{ ucwords(strtolower($headmaster->teacher_name ?? '')) }}
                    {{ ucwords(strtolower($headmaster->suffix ?? '')) }}
                </p>
                <p>NIP: {{ $headmaster->nip ?? '-' }}</p>
            @endif
        </div>
    </div>

</body>
</html>
