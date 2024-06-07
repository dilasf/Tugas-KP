<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('class-subjects.show', $classSubject->id) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <p class="font-semibold text-gray-800 leading-tight text-2xl">
                {{ __('Penilaian Mata Pelajaran ' . $classSubject->subject->subject_name . ' di ' . $classSubject->class->class_name) }}
            </p>
         </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4 flex items-start">
                         {{-- Data Diri Singkat --}}
                         <div class="mr-4">
                            <div class="mb-2 flex items-center">
                                <p class="font-medium text-16px mr-2 inline-block {{ $sidebarOpen ? 'w-[100px]' : 'w-[147px]' }}">Nama Siswa</p>
                                <p class="text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-800 text-16px inline-block">{{ $student->student_name ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-2 flex items-center">
                                <p class="font-medium text-16px mr-2 inline-block {{ $sidebarOpen ? 'w-[100px]' : 'w-[147px]' }}">NIS</p>
                                <p class="text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-800 text-16px inline-block">{{ $student->nis ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <form method="GET" action="{{ route('grade.index', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id]) }}">
                                <label class="block">
                                    <span class="text-16px font-medium">Pilih Semester :</span>
                                        <select name="semester_year_id" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-5 py-1 ml-2 {{ $sidebarOpen ? 'w-48' : 'w-64' }}">
                                            @foreach($semesters as $semester)
                                                <option value="{{ $semester->id }}" {{ $selectedSemesterYearId == $semester->id ? 'selected' : '' }}>
                                                    {{ 'Semester ' . $semester->semester . ' Tahun ' . $semester->year }}
                                                </option>
                                            @endforeach
                                        </select>
                                </label>
                            </form>
                        </div>
                    </div>
                    {{-- End - Data Diri Singkat --}}

                    {{-- Isi Tabel --}}
                    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                        <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                            <x-slot name="header">
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Penilaian</th>
                                    <th>Nilai Akhir</th>
                                    <th>Grade</th>
                                    <th>Aksi</th>
                                </tr>
                            </x-slot>
                                @php $num=1; @endphp

                                {{-- Nilai Pengetahuan --}}
                                <tr>
                                    <td class="text-center">{{ $num++ }}</td>
                                    <td>Pengetahuan</td>
                                    <td class="text-center"> {{ $finalKnowledgeScore }}</td>
                                    <td class="text-center">{{ $knowledgeGrade }}</td>
                                    <td class="text-center">
                                        <x-detail-primary-button tag="a" href="{{ route('grade.detailKnowledgeScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'semesterYearId' => $selectedSemesterYearId]) }}"
                                            class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/detail_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Detail') }}</span>
                                        </x-detail-primary-button>
                                        {{-- {{ dd($selectedSemesterYearId) }} --}}
                                        {{-- <a href="{{ route('grade.detail', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'semesterYearId' => $selectedSemesterYearId]) }}" class="btn btn-primary">
                                            Lihat Detail
                                        </a> --}}
                                    </td>
                                </tr>

                                {{-- Kelompok Keterampilan --}}
                                <tr>
                                    <td class="text-center">{{ $num++ }}</td>
                                    <td>Keterampilan</td>
                                    <td class="text-center">{{ $finalSkillScore }}</td>
                                    <td class="text-center">{{ $skillGrade }}</td>
                                    <td class="text-center">
                                            <x-detail-primary-button tag="a" href="{{ route('grade.detailSkillScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'semesterYearId' => $selectedSemesterYearId]) }}"
                                                class="flex items-center justify-center min-w-[60px]">
                                                <img src="{{ asset('img/detail_logo.png') }}" class="w-[13px] h-[13px]">
                                                <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Detail') }}</span>
                                            </x-detail-primary-button>
                                    </td>
                                </tr>

                                {{-- Kelompok Sikap --}}
                                <tr>
                                    <td class="text-center">{{ $num++ }}</td>
                                    <td>Sikap</td>
                                    <td class="text-center">{{ $finalAttitudeScore }}</td>
                                    <td class="text-center">{{ $attitudeGrade }}</td>
                                    <td class="text-center">
                                            <x-detail-primary-button tag="a" href="{{ route('grade.detailAttitudeScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'semesterYearId' => $selectedSemesterYearId]) }}"
                                                class="flex items-center justify-center min-w-[60px]">
                                                <img src="{{ asset('img/detail_logo.png') }}" class="w-[13px] h-[13px]">
                                                <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Detail') }}</span>
                                            </x-detail-primary-button>
                                    </td>
                                </tr>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

