<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rapor Siswa') }}
        </h2>
    </x-slot>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @php
                // $semesterNumber = $selectedSemester->semester ?? 'N/A';
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
            @endphp

@role('guru_kelas')
    @php
        $rapor = $rapors->first();
    @endphp
    @if ($rapor)
        <!-- Status Alert -->
        @if ($rapor->status === 'waiting_validation')
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                <p class="text-gray-800 text-md">Status Rapor: Menunggu Validasi</p>
            </div>
        @elseif ($rapor->status === 'validated')
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p class="text-gray-800 text-md">Status Rapor: Disetujui</p>
            </div>
        @elseif ($rapor->status === 'rejected')
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="text-gray-800 text-md">Status Rapor: Ditolak</p>
            </div>
        @else
            <div class="bg-gray-100 border-l-4 border-gray-500 text-gray-700 p-4 mb-4" role="alert">
                <p class="text-gray-800 text-md">Status Rapor: Belum Dikirim</p>
            </div>
        @endif
    @endif
@endrole

<!-- Data Diri -->
<div class="mb-4">
    <p class="font-semibold text-xl text-center">RAPOR DAN PROFIL PESERTA DIDIK</p>
    <div class="flex justify-between">
        <div class="py-10">
            <div class="mb-2 flex items-center ">
                <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">Nama Peserta Didik</p>
                <p class="text-gray-800 text-16px mr-2">:</p>
                <p class="text-gray-900 font-medium text-16px">{{ strtoupper($student->student_name ?? 'N/A') }}</p>
            </div>
            <div class="mb-2 flex items-center">
                <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">NIS</p>
                <p class="text-gray-800 text-16px mr-2">:</p>
                <p class="text-gray-900 font-medium text-16px">{{ $student->nis ?? 'N/A' }}</p>
            </div>
            <div class="mb-2 flex items-center">
                <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">Nama Sekolah</p>
                <p class="text-gray-800 text-16px mr-2">:</p>
                <p class="text-gray-900 font-medium text-16px">{{ strtoupper($rapors->first()->school_name ?? 'SDN DAWUAN') }}</p>
            </div>
            <div class="mb-2 flex items-center">
                <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">Alamat Sekolah</p>
                <p class="text-gray-800 text-16px mr-2">:</p>
                <p class="text-gray-900 font-medium text-16px">{{ $rapors->first()->school_address ?? 'KP Pasir Eurih' }}</p>
            </div>
        </div>

        <div class="py-10 px-20">
            <div class="mb-2 flex items-center">
                <p class="font-medium text-16px text-gray-800 mr-2 w-[100px]">Kelas</p>
                <p class="text-gray-800 text-16px mr-2">:</p>
                <p class="text-gray-900 font-medium text-16px">{{ $classLevel }} ({{ $classLevelWord }})</p>
            </div>

            @role('guru_kelas')
                <div class="mb-2 flex items-center">
                    <p class="font-medium text-16px text-gray-900 mr-2 w-[100px]">Semester</p>
                    <p class="text-gray-800 text-16px mr-2">:</p>
                    <form action="{{ route('rapors.index', ['studentId' => $student->id]) }}" method="GET" class="inline-block">
                        <select name="semester_year_id" id="semester_year_id" onchange="this.form.submit();" class="text-gray-900 font-medium text-16px">
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" {{ $semester->id == $selectedSemesterYearId ? 'selected' : '' }}>
                                    Semester {{ $semester->semester }} Tahun {{ $semester->year }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endrole


                            <div class="mb-2 flex items-center">
                                <p class="font-medium text-16px text-gray-900 mr-2 w-[100px]">Semester</p>
                                <p class="text-gray-800 text-16px mr-2">:</p>
                                @php
                                    $selectedSemester = $semesters->firstWhere('id', $selectedSemesterYearId);
                                    $semesterNumber = $selectedSemester ? $selectedSemester->semester : 'N/A';
                                    $numberWords = [
                                        1 => 'Satu',
                                        2 => 'Dua',
                                    ];
                                    $semesterWord = isset($numberWords[$semesterNumber]) ? $numberWords[$semesterNumber] : 'N/A';
                                @endphp
                                <p class="text-gray-900 font-medium text-16px">{{ $semesterNumber }} ({{ $semesterWord }})</p>
                            </div>

                            <div class="mb-9 flex items-center">
                                <p class="font-medium text-16px text-gray-900 mr-2 w-[100px]">Tahun Ajaran</p>
                                <p class="text-gray-800 text-16px mr-2">:</p>
                                <p class="text-gray-900 font-medium text-16px">{{  $semesterNumber = $selectedSemester ? $selectedSemester->year : 'N/A' }}</p>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- END Data Diri --}}

           {{-- Kompetensi Sikap --}}
            <div class="flex justify-between items-center">
                <p class="font-semibold text-md">A. Kompetensi Sikap</p>
            </div>
            <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                    <x-slot name="header">
                        <tr>
                            <th colspan="4">Deskripsi</th>
                        </tr>
                    </x-slot>
                    @php $num = 1; @endphp

                    {{-- Sikap Spiritual --}}
                    <tr>
                        <td class="text-center">{{ $num++ }}</td>
                        <td class="font-semibold">Sikap Spiritual</td>
                        <td>
                            @if ($rapors->isNotEmpty() && $rapors->first()->spiritual_attitude !== null)
                                {{ $rapors->first()->spiritual_attitude }}
                            @else
                                <span class="text-gray-400">Data tidak tersedia</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @role('guru_kelas')
                            @if ($rapors->isNotEmpty() && !in_array($rapors->first()->status, ['waiting_validation', 'validated']))
                                <x-edit-primary-button tag="a" href="{{ route('rapors.editAspect', ['studentId' => $student->id, 'raporId' => $rapors->first()->id, 'aspectName' => 'Sikap Spiritual']) }}" class="flex items-center justify-center min-w-[60px]">
                                    <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                    <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                </x-edit-primary-button>
                            @endif
                            @endrole
                        </td>
                    </tr>

                    {{-- Sikap Sosial --}}
                    <tr>
                        <td class="text-center">{{ $num++ }}</td>
                        <td class="font-semibold">Sikap Sosial</td>
                        <td>
                            @if ($rapors->isNotEmpty() && $rapors->first()->social_attitudes !== null)
                                {{ $rapors->first()->social_attitudes }}
                            @else
                                <span class="text-gray-400">Data tidak tersedia</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @role('guru_kelas')
                            @if ($rapors->isNotEmpty() && !in_array($rapors->first()->status, ['waiting_validation', 'validated']))
                                <x-edit-primary-button tag="a" href="{{ route('rapors.editAspect', ['studentId' => $student->id, 'raporId' => $rapors->first()->id, 'aspectName' => 'Sikap Sosial']) }}" class="flex items-center justify-center min-w-[60px]">
                                    <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                    <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                </x-edit-primary-button>
                            @endif
                            @endrole
                        </td>
                    </tr>
                </x-table>
            </div>
            {{-- End Kompetensi Sikap --}}


                {{-- Tabel Nilai --}}
                <div class="mt-8">
                <p class="font-semibold text-md">B. Kompetensi Pengetahuan dan Keterampilan</p>
                    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                        <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                            <x-slot name="header">
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Mata Pelajaran</th>
                                    <th colspan="3">Pengetahuan</th>
                                    <th rowspan="2">KKM</th>
                                    <th colspan="3">Keterampilan</th>

                                    @php
                                        $status = $rapors->isNotEmpty() ? $rapors->first()->status : null;
                                    @endphp
                                    @if (!in_array($status, ['waiting_validation', 'validated']))
                                        @role('guru_kelas')
                                        <th rowspan="2">Aksi</th>
                                        @endrole
                                    @endif
                                </tr>
                                <tr>
                                    <th>Nilai</th>
                                    <th>Predikat</th>
                                    <th>Deskripsi</th>
                                    <th>Nilai</th>
                                    <th>Predikat</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </x-slot>
                            @php $num = 1; @endphp
                            @foreach($rapors->groupBy('grade.classSubject.subject.id') as $subjectRapors)
                                @php
                                    $firstRapor = $subjectRapors->first();
                                    $subject = optional($firstRapor->grade->classSubject->subject)->subject_name ?? 'N/A';
                                    $averageKnowledgeScore = round($firstRapor->grade->average_knowledge_score) ?? '0';
                                    $gradeKnowledge = $firstRapor->grade->gradeKnowledge ?? '-';
                                    $descriptionKnowledge = $firstRapor->grade->descriptionKnowledge ?? 'Tidak Ada Deskripsi';
                                    $averageAttitudeScore = round($firstRapor->grade->average_attitude_score) ?? '0';
                                    $gradeAttitude = $firstRapor->grade->gradeAttitude ?? '-';
                                    $descriptionAttitude = $firstRapor->grade->descriptionAttitude ?? 'Tidak Ada Deskripsi';
                                    $averageSkillScore = round($firstRapor->grade->average_skill_score) ?? '0';
                                    $gradeSkill = $firstRapor->grade->gradeSkill ?? '-';
                                    $descriptionSkill = $firstRapor->grade->descriptionSkill ?? 'Tidak Ada Deskripsi';
                                    $kkm = optional($firstRapor->grade->classSubject->subject)->kkm ?? '0';
                                    // $status = $firstRapor->status ?? null;
                                @endphp
                                <tr class="text-center">
                                    <td>{{ $num++ }}</td>
                                    <td>{{ $subject }}</td>
                                    <td class="{{ $averageKnowledgeScore < $kkm ? 'text-red-500' : 'text-gray-800' }}">{{ $averageKnowledgeScore }}</td>
                                    <td class="{{ $averageKnowledgeScore < $kkm ? 'text-red-500' : 'text-gray-800' }}">{{ $gradeKnowledge }}</td>
                                    <td>{{ $descriptionKnowledge }}</td>
                                    <td>{{ $kkm }}</td>
                                    <td class="{{ $averageSkillScore < $kkm ? 'text-red-500' : 'text-gray-800' }}">{{ $averageSkillScore }}</td>
                                    <td class="{{ $averageSkillScore < $kkm ? 'text-red-500' : 'text-gray-800' }}">{{ $gradeSkill }}</td>
                                    <td>{{ $descriptionSkill }}</td>
                                   @if (!in_array($status, ['waiting_validation', 'validated']))
                                    @role('guru_kelas')
                                    <td>
                                        <x-edit-primary-button tag="a" href="{{ route('rapors.edit', ['studentId' => $student->id, 'semesterYearId' => $selectedSemesterYearId]) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    </td>
                                    @endrole
                                @endif

                                </tr>
                            @endforeach
                        </x-table>
                    </div>
                </div>
                    {{-- End Tabel Nilai --}}

                   {{-- Ekstrakurikuler --}}
                <div class="mt-8">
                    <div class="flex justify-between items-center">
                        <p class="font-semibold text-md">C. Ekstra Kurikuler</p>
                        @php
                            $status = $rapors->isNotEmpty() ? $rapors->first()->status : null;
                        @endphp
                        @if (!in_array($status, ['waiting_validation', 'validated']))
                            @role('guru_kelas')
                            <x-primary-button tag="a" href="{{ route('extracurriculars.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId]) }}" class="font-semibold inline-flex items-center" style="padding: 0.5rem 1rem;">
                                <span class="text-12px">{{ __('+ Tambah Data') }}</span>
                            </x-primary-button>
                            @endrole
                        @endif
                    </div>
                    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                        <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                            <x-slot name="header">
                                <tr>
                                    <th>No</th>
                                    <th>Kegiatan Ekstrakurikuler</th>
                                    <th>Keterangan</th>
                                    @if (!in_array($status, ['waiting_validation', 'validated']))
                                        @role('guru_kelas')
                                        <th>Aksi</th>
                                        @endrole
                                    @endif
                                </tr>
                            </x-slot>
                            @php
                                $num = 1;
                                $hasExtracurricular = false;
                            @endphp
                            @foreach ($rapors as $rapor)
                                @foreach ($rapor->extracurricular as $extracurricular)
                                    @php $hasExtracurricular = true; @endphp
                                    <tr>
                                        <td>{{ $num++ }}</td>
                                        <td>{{ $extracurricular->activity }}</td>
                                        <td>{{ $extracurricular->description }}</td>
                                        @if (!in_array($status, ['waiting_validation', 'validated']))
                                            @role('guru_kelas')
                                            <td class="text-center">
                                                <x-edit-primary-button tag="a" href="{{ route('extracurriculars.edit', ['studentId' => $student->id, 'extracurricularId' => $extracurricular->id]) }}" class="flex items-center justify-center min-w-[60px]">
                                                    <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                                    <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                                </x-edit-primary-button>

                                                <x-danger-button
                                                    x-data=""
                                                    x-on:click.prevent="
                                                        $dispatch('open-modal', 'confirm-data-deletion');
                                                        $dispatch('set-action', '{{ route('extracurriculars.destroy', ['extracurricularId' => $extracurricular->id]) }}');
                                                    "
                                                    class="flex items-center justify-center min-w-[60px]">
                                                    <img src="{{ asset('img/garbage_logo.png') }}" class="w-[13px] h-[13px]">
                                                    <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Hapus') }}</span>
                                                </x-danger-button>
                                            </td>
                                            @endrole
                                        @endif
                                    </tr>
                                @endforeach
                            @endforeach

                            @if (!$hasExtracurricular)
                                @for ($i = $num; $i <= 3; $i++)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td colspan="3" class="text-center">-</td>
                                    </tr>
                                @endfor
                            @endif
                        </x-table>
                    </div>
                </div>
                {{-- End Ekstrakurikuler --}}


                  {{-- Saran --}}
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <p class="font-semibold text-md">D. Saran</p>

                        @php
                            $status = $rapors->isNotEmpty() ? $rapors->first()->status : null;
                        @endphp
                        @if (!in_array($status, ['waiting_validation', 'validated']))
                            @role('guru_kelas')
                            <x-primary-button tag="a" href="{{ route('rapors.editSuggestion', ['studentId' => $student->id, 'semesterYearId' => $selectedSemesterYearId]) }}">
                                <span>{{ __('Masukkan Saran') }}</span>
                            </x-primary-button>
                            @endrole
                        @endif

                    </div>
                    <div class="border border-black text-black p-5 rounded-md mt-2" style="min-height: 7rem;">
                        <p class="text-sm text-black">
                            {{ $rapors->first()->suggestion ?? 'Tidak ada saran yang tersedia' }}
                        </p>
                    </div>
                </div>
                {{-- End Saran --}}

                {{-- Tinggi Badan --}}
                <div class="mt-8">
                    <p class="font-semibold text-md">E. Data Tinggi Berat Badan</p>
                    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                        <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                            <x-slot name="header">
                                <tr>
                                    <th>No</th>
                                    <th>Aspek yang dinilai</th>
                                    <th>Jumlah</th>
                                    @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                                    @role('guru_kelas')
                                    <th>Aksi</th>
                                    @endrole
                                    @endif
                                </tr>
                            </x-slot>

                            @php $num = 1; @endphp

                            <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td class="font-semibold">Tinggi Badan</td>
                                <td class="text-center">
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
                                @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                                @role('guru_kelas')
                                <td class="text-center">
                                    @if ($heightWeight)
                                        <x-edit-primary-button tag="a" href="{{ route('height_weights.edit', ['studentId' => $student->id, 'heightWeightId' => $heightWeight->id, 'aspectName' => 'Tinggi Badan', 'semester_year_id' => $selectedSemesterYearId]) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    @endif
                                </td>
                                @endrole
                                @endif
                            </tr>

                            <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td class="font-semibold">Berat Badan</td>
                                <td class="text-center">
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
                                @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                                @role('guru_kelas')
                                <td class="text-center">
                                    @if ($weightHeight)
                                        <x-edit-primary-button tag="a" href="{{ route('height_weights.edit', ['studentId' => $student->id, 'heightWeightId' => $weightHeight->id, 'aspectName' => 'Berat Badan', 'semester_year_id' => $selectedSemesterYearId]) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    @endif
                                </td>
                                @endrole
                                @endif
                            </tr>
                        </x-table>
                    </div>
                </div>
                {{-- End Tinggi Badan --}}

                {{-- Kesehatan --}}
                <div class="mt-8">
                    <div class="flex justify-between items-center">
                        <p class="font-semibold text-md">F. Kondisi Kesehatan</p>
                    </div>
                    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                        <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                            <x-slot name="header">
                                <tr>
                                    <th>No</th>
                                    <th>Aspek Fisik</th>
                                    <th>Keterangan</th>
                                    @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                                    @role('guru_kelas')
                                    <th>Aksi</th>
                                    @endrole
                                    @endif
                                </tr>
                            </x-slot>
                            @php $num = 1; @endphp

                            <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td class="font-semibold">Pendengaran</td>
                                <td class="text-center">
                                    @if ($rapors->isNotEmpty() && $rapors[0]->health && $rapors[0]->health->hearing !== null)
                                        {{ $rapors[0]->health->hearing }}
                                    @else
                                        <span class="text-gray-400">Data tidak tersedia</span>
                                    @endif
                                </td>
                                @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                                @role('guru_kelas')
                                <td class="text-center">
                                    @if ($rapors->isNotEmpty() && $rapors[0]->health)
                                        <x-edit-primary-button tag="a" href="{{ route('healths.edit', ['studentId' => $student->id, 'healthId' => $rapors[0]->health->id, 'aspectName' => 'Pendengaran']) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    @else
                                        <x-edit-primary-button tag="a" href="{{ route('healths.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId, 'aspectName' => 'Pendengaran']) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    @endif
                                </td>
                                @endrole
                                @endif
                            </tr>

                            <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td class="font-semibold">Penglihatan</td>
                                <td class="text-center">
                                    @if ($rapors->isNotEmpty() && $rapors[0]->health && $rapors[0]->health->vision !== null)
                                        {{ $rapors[0]->health->vision }}
                                    @else
                                        <span class="text-gray-400">Data tidak tersedia</span>
                                    @endif
                                </td>
                                @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                                @role('guru_kelas')
                                <td class="text-center">
                                    @if ($rapors->isNotEmpty() && $rapors[0]->health)
                                        <x-edit-primary-button tag="a" href="{{ route('healths.edit', ['studentId' => $student->id, 'healthId' => $rapors[0]->health->id, 'aspectName' => 'Penglihatan']) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    @else
                                        <x-edit-primary-button tag="a" href="{{ route('healths.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId, 'aspectName' => 'Penglihatan']) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    @endif
                                </td>
                                @endrole
                                @endif
                            </tr>

                            <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td class="font-semibold">Gigi</td>
                                <td class="text-center">
                                    @if ($rapors->isNotEmpty() && $rapors[0]->health && $rapors[0]->health->tooth !== null)
                                        {{ $rapors[0]->health->tooth }}
                                    @else
                                        <span class="text-gray-400">Data tidak tersedia</span>
                                    @endif
                                </td>
                                @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                                @role('guru_kelas')
                                <td class="text-center">
                                    @if ($rapors->isNotEmpty() && $rapors[0]->health)
                                        <x-edit-primary-button tag="a" href="{{ route('healths.edit', ['studentId' => $student->id, 'healthId' => $rapors[0]->health->id, 'aspectName' => 'Gigi']) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    @else
                                        <x-edit-primary-button tag="a" href="{{ route('healths.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId, 'aspectName' => 'Gigi']) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    @endif
                                </td>
                                @endrole
                                @endif
                            </tr>
                        </x-table>
                    </div>
                </div>
                {{-- End Kesehatan --}}


                  {{-- Prestasi --}}
                <div class="mt-8">
                    <div class="flex justify-between items-center">
                        <p class="font-semibold text-md">G. Prestasi</p>
                        @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                        @role('guru_kelas')
                        <x-primary-button tag="a" href="{{ route('achievements.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId]) }}" class="font-semibold inline-flex items-center"
                            style="padding: 0.5rem 1rem;">
                            <span class="text-12px">{{ __('+ Tambah Data') }}</span>
                        </x-primary-button>
                        @endrole
                        @endif
                    </div>
                    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                        <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                            <x-slot name="header">
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Prestasi</th>
                                    <th>Keterangan</th>
                                    @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                                    @role('guru_kelas')
                                    <th>Aksi</th>
                                    @endrole
                                    @endif
                                </tr>
                            </x-slot>
                            @php $num = 1; @endphp
                            @php $hasAchievements = false; @endphp

                            @foreach ($rapors as $rapor)
                                @foreach ($rapor->achievement ?? [] as $achievement)
                                    <tr>
                                        <td>{{ $num++ }}</td>
                                        <td>{{ $achievement->achievement_type }}</td>
                                        <td>{{ $achievement->description }}</td>

                                        @if (!in_array($rapors->isEmpty() ? null : $rapors->first()->status, ['waiting_validation', 'validated']))
                                        @role('guru_kelas')
                                        <td class="text-center">
                                            <!-- Tombol Edit -->
                                            <x-edit-primary-button tag="a" href="{{ route('achievements.edit', ['studentId' => $student->id, 'achievementId' => $achievement->id]) }}" class="flex items-center justify-center min-w-[60px]">
                                                <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                                <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                            </x-edit-primary-button>

                                            <!-- Tombol Hapus -->
                                            <x-danger-button
                                                x-data=""
                                                x-on:click.prevent="
                                                    $dispatch('open-modal', 'confirm-data-deletion');
                                                    $dispatch('set-action', '{{ route('achievements.destroy', ['achievementId' => $achievement->id]) }}');
                                                "
                                                class="flex items-center justify-center min-w-[60px]">
                                                <img src="{{ asset('img/garbage_logo.png') }}" class="w-[13px] h-[13px]">
                                                <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Hapus') }}</span>
                                            </x-danger-button>
                                        </td>
                                        @endrole
                                        @endif
                                    </tr>
                                    @php $hasAchievements = true; @endphp
                                @endforeach
                            @endforeach

                            @if (!$hasAchievements)
                                @for ($i = $num; $i <= 3; $i++)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td colspan="3" class="text-center">-</td>
                                    </tr>
                                @endfor
                            @endif
                        </x-table>
                    </div>
                </div>
                {{-- End Prestasi --}}

                    {{-- Tabel Ketidakhadiran --}}
                    <div class="mt-8">
                        <p class="font-semibold text-md">H. Ketidakhadiran</p>
                        <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                            <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                                <x-slot name="header">
                                    <tr>
                                        <th>No</th>
                                        <th>Keterangan</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </x-slot>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="font-semibold">Sakit</td>
                                        <td class="text-center">{{ $totalSick }} hari</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td class="font-semibold">Ijin</td>
                                        <td class="text-center">{{ $totalPermission }} hari</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td class="font-semibold">Tanpa Keterangan</td>
                                        <td class="text-center">{{ $totalUnexcused }} hari</td>
                                    </tr>

                            </x-table>
                        </div>
                    </div>
                    {{-- End Tabel Ketidakhadiran --}}

                    {{-- Additional text below the table --}}
                    <div class="text-md text-gray-800 mt-10 px-8 float-right">
                        Cianjur,
                        @if ($rapors->isEmpty())
                            Belum Dicetak
                        @else
                            {{ $rapors->first()->print_date ? \Carbon\Carbon::parse($rapors->first()->print_date)->locale('id')->isoFormat('DD MMMM YYYY') : 'Belum Dicetak' }}
                        @endif
                    </div>


                    <div class="flex justify-between mt-20">
                        <div class="text-md text-gray-800 px-8">
                            <p class="text-center">Orang Tua/Wali</p>
                            <hr class="mt-20 w-1/8 border border-dotted border-gray-800 mx-auto my-1">
                        </div>
                        <div class="text-md text-gray-800 px-8">
                            <p class="text-center">Guru Kelas</p>
                            @role('guru_kelas')
                                @php
                                    $rapor = $rapors->first();
                                @endphp
                                @if ($rapor)
                                    <div class="rapor-item text-center">
                                        @if ($rapor->status === 'not_sent' || $rapor->status === 'rejected')
                                            <form action="{{ route('send.report', ['raporId' => $rapor->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Kirimkan</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @endrole

                            <div class="flex flex-col items-center">
                                <p class="font-semibold underline mt-12 text-center">
                                    {{ ucwords(strtolower($student->class->teacher->prefix ?? '')) }}
                                    {{ ucwords(strtolower($student->class->teacher->teacher_name ?? '')) }}
                                    {{ ucwords(strtolower($student->class->teacher->suffix ?? '')) }}
                                </p>
                                <p class="text-center">NIP: {{ $student->class->teacher->nip ?? '-' }}</p>
                            </div>
                        </div>

                        </div>


                    <div class="text-center text-md text-gray-800 mt-8">
                        @if ($headmaster && $headmaster->typesOfCAR === 'Kepala Sekolah')
                            <p>Mengetahui</p>
                            <p>Kepala Sekolah</p>
                            <div class="flex flex-col items-center mt-4">
                                @role('kepala_sekolah')
                                @php
                                    $rapor = $rapors->firstWhere('status', 'waiting_validation');
                                @endphp
                                @if ($rapor)
                                <div class="flex space-x-4"> <!-- Add flex container for buttons -->
                                    <form action="{{ route('rapors.validation.approve', ['id' => $rapor->id]) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-4 hover:bg-green-700">Validasi</button>
                                    </form>
                                    <form action="{{ route('rapors.validation.reject', ['id' => $rapor->id]) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mt-4 hover:bg-red-700">Tolak</button>
                                    </form>
                                </div>
                                @endif
                                @endrole

                                <p class="font-semibold underline underline-offset-2 mt-20 text-center">
                                    {{ ucwords(strtolower($headmaster->prefix ?? '')) }}
                                    {{ ucwords(strtolower($headmaster->teacher_name ?? '')) }}
                                    {{ ucwords(strtolower($headmaster->suffix ?? '')) }}
                                </p>
                                <p class="font-weight-bold text-center">NIP: {{ $headmaster->nip ?? '-' }}</p>
                            </div>

                        @endif
                    </div>



                    </div>


                </div>
            </div>

             </div>
            </div>
        </div>

        {{-- Modal Hapus --}}
        <x-modal name="confirm-data-deletion" focusable maxWidth="xl">
            <form method="post" x-bind:action="action" class="p-6 flex items-center">
                @csrf
                @method('delete')

                <div class="flex items-start">
                    <!-- Icon -->
                    <span class="flex-shrink-0 inline-flex justify-center items-center size-[46px] sm:w-[62px] sm:h-[62px] rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100 mr-6">
                        <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                    </span>
                    <!-- End Icon -->

                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Apakah anda yakin akan menghapus data?') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Setelah proses dilaksanakan. Data akan dihilangkan secara permanen.') }}
                        </p>
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Batal') }}
                            </x-secondary-button>
                            <x-danger-button class="ml-3">
                                {{ __('Hapus!!!') }}
                            </x-danger-button>
                        </div>
                    </div>
                </div>
            </form>
        </x-modal>
</x-app-layout>
