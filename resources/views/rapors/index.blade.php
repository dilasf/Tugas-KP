<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rapor Siswa') }}
        </h2>
    </x-slot>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if($rapors->count() > 0)
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
            @endphp

                {{-- Data Diri --}}
                <div class="mb-4">
                    <p class="font-semibold text-xl text-center">RAPOR DAN PROFIL PESERTA DIDIK</p>
                    <div class="flex justify-between">
                        <div class="py-10">
                            <div class="mb-2 flex items-center ">
                                <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">Nama Peserta Didik</p>
                                <p class="text-gray-800 text-16px mr-2">:</p>
                                <p class="text-gray-900 font-medium text-16px">{{ strtoupper ($student->student_name ?? 'N/A') }}</p>
                            </div>
                            <div class="mb-2 flex items-center">
                                <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">NIS</p>
                                <p class="text-gray-800 text-16px mr-2">:</p>
                                <p class="text-gray-900 font-medium text-16px">{{ $student->nis ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-2 flex items-center">
                                <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">Nama Sekolah</p>
                                <p class="text-gray-800 text-16px mr-2">:</p>
                                <p class="text-gray-900 font-medium text-16px">{{ strtoupper ($rapors->first()->school_name ?? 'SDN DAWUAN') }}</p>
                            </div>
                            <div class="mb-2 flex items-center">
                                <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">Alamat Sekolah</p>
                                <p class="text-gray-800 text-16px mr-2">:</p>
                                <p class="text-gray-900 font-medium text-16px">{{ $rapors->first()->school_address ?? 'KP Pasir Eurih' }}</p>
                            </div>
                        </div>
                        <div class="py-10">
                            <div class="mb-2 flex items-center">
                                <p class="font-medium text-16px text-gray-800 mr-2 w-[100px]">Kelas</p>
                                <p class="text-gray-800 text-16px mr-2">:</p>
                                <p class="text-gray-900 font-medium text-16px">{{ $classLevel }} ({{ $classLevelWord }})</p>
                            </div>
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
                        </div>
                    </div>
                </div>
                {{-- END Data Diri --}}

                {{-- Tabel Nilai --}}
                <p class="font-semibold text-md">B. Kompetensi Pengetahuan dan Keterampilan</p>
                    <!-- Tabel Nilai Mata Pelajaran -->
                    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                        <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                            <x-slot name="header">
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Mata Pelajaran</th>
                                    <th colspan="3">Pengetahuan</th>
                                    <th rowspan="2">KKM</th>
                                    <th colspan="3">Keterampilan</th>
                                    <th rowspan="2">Aksi</th>
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
                                    <td>
                                        <x-edit-primary-button tag="a" href="{{ route('rapors.edit', ['studentId' => $student->id, 'semesterYearId' => $selectedSemesterYearId]) }}" class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                        </x-edit-primary-button>
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    </div>
                    {{-- End Tabel Nilai --}}

                    {{-- Ekstrakurikuler --}}
                    <div class="mt-8">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold text-md">C. Ekstra Kurikuler</p>

                            <x-primary-button tag="a" href="{{ route('extracurriculars.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId]) }}" class="font-semibold inline-flex items-center" style="padding: 0.5rem 1rem;">
                                <span class="text-12px">{{ __('+ Tambah Data') }}</span>
                            </x-primary-button>
                        </div>
                        <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                            <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                                <x-slot name="header">
                                    <tr>
                                        <th>No</th>
                                        <th>Kegiatan Ekstrakurikuler</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
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
                        <x-primary-button tag="a" href="{{ route('rapors.editSuggestion', ['studentId' => $student->id]) }}">
                            <span>{{ __('Masukkan Saran') }}</span>
                        </x-primary-button>
                    </div>
                        <div class="border border-black text-black p-5 rounded-md mt-2" style="min-height: 7rem;">
                            <p class="text-sm text-black">
                                {{ $rapors->first()->suggestion ?? 'Tidak ada saran yang tersedia' }}
                            </p>
                        </div>
                  </div>
                  {{-- End Saran --}}

{{-- Tabel Tinggi Berat Badan --}}
<div class="mt-8">
    <p class="font-semibold text-md">E. Data Tinggi Berat Badan</p>
    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
        <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
            <x-slot name="header">
                <tr>
                    <th>No</th>
                    <th>Aspek yang dinilai</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </x-slot>
            @php $num = 1; @endphp

            <tr>
                <td class="text-center">{{ $num++ }}</td>
                <td class="font-semibold">Tinggi Badan</td>
                <td class="text-center">{{ $student->heightWeight ? $student->heightWeight->height ?? '-' : '-' }} cm</td>
                <td class="text-center">
                    @if ($student->heightWeight)
                        <x-edit-primary-button tag="a" href="{{ route('height_weights.edit', ['studentId' => $student->id, 'heightWeightId' => $student->heightWeight->id, 'aspectName' => 'Tinggi Badan']) }}" class="flex items-center justify-center min-w-[60px]">
                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                            <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                        </x-edit-primary-button>
                    @else
                        <span class="text-gray-400">Data tidak tersedia</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="text-center">{{ $num++ }}</td>
                <td class="font-semibold">Berat Badan</td>
                <td class="text-center">{{ $student->heightWeight ? $student->heightWeight->weight ?? '-' : '-' }} kg</td>
                <td class="text-center">
                    <x-edit-primary-button tag="a"  href="{{ route('height_weights.edit', ['studentId' => $student->id, 'heightWeightId' => $student->heightWeight->id, 'aspectName' => 'Berat Badan']) }}" class="flex items-center justify-center min-w-[60px]">
                        <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                        <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                    </x-edit-primary-button>
                </td>
            </tr>
            <tr>
                {{-- <td class="text-center">{{ $num++ }}</td>
                <td class="font-semibold">Ukuran Kepala</td>
                <td class="text-center">{{ $student->heightWeight ? $student->heightWeight->head_size ?? '-' : '-' }} cm</td>
                <td class="text-center">
                    <x-edit-primary-button tag="a" href="{{ $student->heightWeight ? route('height_weights.edit', ['studentId' => $student->id, 'heightWeightId' => $student->heightWeight->id, 'aspectName' => 'Ukuran Kepala']) : route('height_weights.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId, 'aspectName' => 'Ukuran Kepala']) }}" class="flex items-center justify-center min-w-[60px]">
                        <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                        <span x-show="!$sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                    </x-edit-primary-button>
                </td> --}}
            </tr>

        </x-table>
    </div>
</div>
{{-- End Tabel Height Weights --}}


            {{-- Kesehatan --}}
            <div class="mt-8">
                <div class="flex justify-between items-center">
                    <p class="font-semibold text-md">F. Kesehatan</p>
                    {{-- Tambah Data Button --}}
                </div>
                <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                    <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                        <x-slot name="header">
                            <tr>
                                <th>No</th>
                                <th>Aspek Fisik</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </x-slot>
                        @php $num = 1; @endphp

                            <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td class="font-semibold">Pendengaran</td>
                                <td class="text-center">{{ $rapors[0]->health ? $rapors[0]->health->hearing ?? '-' : '-' }}</td>
                                <td class="text-center">
                                    <x-edit-primary-button tag="a" href="{{ $rapors[0]->health ? route('healths.edit', ['studentId' => $student->id, 'healthId' => $rapors[0]->health->id, 'aspectName' => 'Pendengaran']) : route('healths.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId, 'aspectName' => 'Pendengaran']) }}"
                                        class="flex items-center justify-center min-w-[60px]">
                                        <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                        <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                    </x-edit-primary-button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td class="font-semibold">Penglihatan</td>
                                <td class="text-center">{{ $rapors[0]->health ? $rapors[0]->health->vision ?? '-' : '-' }}</td>
                                <td class="text-center">
                                    <x-edit-primary-button tag="a" href="{{ $rapors[0]->health ? route('healths.edit', ['studentId' => $student->id, 'healthId' => $rapors[0]->health->id, 'aspectName' => 'Penglihatan']) : route('healths.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId, 'aspectName' => 'Penglihatan']) }}"
                                        class="flex items-center justify-center min-w-[60px]">
                                        <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                        <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                    </x-edit-primary-button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td class="font-semibold">Gigi</td>
                                <td class="text-center">{{ $rapors[0]->health ? $rapors[0]->health->tooth ?? '-' : '-' }}</td>
                                <td class="text-center">
                                    <x-edit-primary-button tag="a" href="{{ $rapors[0]->health ? route('healths.edit', ['studentId' => $student->id, 'healthId' => $rapors[0]->health->id, 'aspectName' => 'Gigi']) : route('healths.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId, 'aspectName' => 'Gigi']) }}"
                                        class="flex items-center justify-center min-w-[60px]">
                                        <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                        <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                    </x-edit-primary-button>
                                </td>
                            </tr>

                    </x-table>
                </div>
            </div>
            {{-- End Kesehatan --}}

    {{-- Prestasi --}}
    <div class="mt-8">
        <div class="flex justify-between items-center">
            <p class="font-semibold text-md">G. Prestasi</p>

            <x-primary-button tag="a" href="{{ route('achievements.create', ['studentId' => $student->id, 'semester_year_id' => $selectedSemesterYearId]) }}" class="font-semibold inline-flex items-center"
                style="padding: 0.5rem 1rem;">
                <span class="text-12px">{{ __('+ Tambah Data') }}</span>
            </x-primary-button>
        </div>
        <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
            <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                <x-slot name="header">
                    <tr>
                        <th>No</th>
                        <th>Jenis Prestasi</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
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
                            <td class="text-center">
                                <!-- Tombol Edit -->
                                <x-edit-primary-button tag="a" href="{{ route('achievements.edit', ['studentId' => $student->id, 'achievementId' => $achievement->id]) }}" class="flex items-center justify-center min-w-[60px]">
                                    <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                    <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
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
                                    <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Hapus') }}</span>
                                </x-danger-button>
                            </td>
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

                    </div>
                @else
                    <p class="text-red-500">Data rapor tidak ditemukan.</p>
                @endif
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
