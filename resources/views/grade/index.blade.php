<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('class-subjects.show', $classSubject->id) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <p class="font-semibold text-gray-800 leading-tight text-2xl">
                {{ __('Penilaian ' . $classSubject->class->class_name) }}
            </p>
         </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <p class="font-semibold text-xl text-center py-3"> {{ __('Penilaian Mata Pelajaran ' . $classSubject->subject->subject_name)}} </p>

                {{-- Data Diri Singkat --}}
                <div class="mb-4 flex items-start justify-between py-4">
                    <div>
                        <div class="mb-2 flex items-center">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Nama Siswa</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-800 text-16px inline-block">{{ ucwords(strtolower($student->student_name)) ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2 flex items-center">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">NIS</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-800 text-16px inline-block">{{ $student->nis ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-16px text-gray-600 mr-2 inline-block">Semester :</p>
                        <form action="{{ route('grade.index', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id]) }}" method="GET" class="inline-block">
                            <select name="semester" id="semester" onchange="this.form.submit();" class="text-gray-800 text-16px">
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $semester->id == $selectedSemesterYearId ? 'selected' : '' }}>
                                        Semester {{ $semester->semester }} Tahun {{ $semester->year }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                {{-- End - Data Diri Singkat --}}

                {{-- Tabel --}}
                <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                    <x-table header="Penilaian Siswa" class="overflow-x-auto mx-auto">
                        <x-slot name="header">
                            <tr>
                                <th>No</th>
                                <th>Jenis Penilaian</th>
                                <th>Nilai Akhir</th>
                                <th>Grade</th>
                                <th>Aksi</th>
                            </tr>
                        </x-slot>
                        {{-- Pengetahuan --}}
                        <tr>
                            <td class="text-center">1</td>
                            <td>Pengetahuan</td>
                            <td class="text-center">{{ $averageKnowledgeScore ?? '0' }}</td>
                            <td class="text-center">{{ $knowledgeGrade }}</td>
                            <td class="text-center">
                                <x-detail-primary-button tag="a" href="{{ route('grade.detailKnowledgeScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id]) }}"
                                    class="flex items-center justify-center min-w-[60px]">
                                    <img src="{{ asset('img/detail_logo.png') }}" class="w-[13px] h-[13px]">
                                    <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Detail') }}</span>
                                </x-detail-primary-button>
                            </td>
                        </tr>
                        {{-- Sikap --}}
                        <tr>
                            <td class="text-center">2</td>
                            <td>Sikap</td>
                            <td class="text-center">{{ $averageAttitudeScore ?? '0' }}</td>
                            <td class="text-center">{{ $attitudeGrade }}</td>
                            <td class="text-center">
                                <x-detail-primary-button tag="a" href="{{ route('grade.detailAttitudeScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id]) }}"
                                    class="flex items-center justify-center min-w-[60px]">
                                    <img src="{{ asset('img/detail_logo.png') }}" class="w-[13px] h-[13px]">
                                    <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Detail') }}</span>
                                </x-detail-primary-button>
                            </td>
                        </tr>
                        
                        {{-- Keterampilan --}}
                        <tr>
                            <td class="text-center">3</td>
                            <td>Keterampilan</td>
                            <td class="text-center">{{ $averageSkillScore ?? '0' }}</td>
                            <td class="text-center">{{ $skillGrade }}</td>
                            <td class="text-center">
                                <x-detail-primary-button tag="a" href="{{ route('grade.detailSkillScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id]) }}"
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
</x-app-layout>
