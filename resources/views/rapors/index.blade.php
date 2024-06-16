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
                                <form action="{{ route('rapors.index', ['id' => $student->id]) }}" method="GET" class="inline-block">
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
                                    $averageKnowledgeScore = $firstRapor->grade->average_knowledge_score ?? '0';
                                    $gradeKnowledge = $firstRapor->grade->gradeKnowledge ?? '-';
                                    $descriptionKnowledge = $firstRapor->grade->descriptionKnowledge ?? 'Tidak Ada Deskripsi';
                                    $averageAttitudeScore = $firstRapor->grade->average_attitude_score ?? '0';
                                    $gradeAttitude = $firstRapor->grade->gradeAttitude ?? '-';
                                    $descriptionAttitude = $firstRapor->grade->descriptionAttitude ?? 'Tidak Ada Deskripsi';
                                    $averageSkillScore = $firstRapor->grade->average_skill_score ?? '0';
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
                                    <td class="{{ $averageKnowledgeScore < $kkm ? 'text-red-500' : 'text-gray-800' }}">{{ $averageSkillScore }}</td>
                                    <td class="{{ $averageKnowledgeScore < $kkm ? 'text-red-500' : 'text-gray-800' }}">{{ $gradeSkill }}</td>
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
                @else
                    <p class="text-red-500">Data rapor tidak ditemukan.</p>
                @endif
            </div>
        </div>
</x-app-layout>
