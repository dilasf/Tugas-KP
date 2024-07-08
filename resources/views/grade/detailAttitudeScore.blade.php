<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('grade.index', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id]) }}">
                    <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
                </a>
                <p class="font-semibold text-gray-800 leading-tight text-2xl">
                    {{ __('Penilaian ' . $classSubject->class->class_name . ' - Semester ' . ($semesters->firstWhere('id', $selectedSemesterYearId)->semester ?? '-') . ' Tahun ' . ($semesters->firstWhere('id', $selectedSemesterYearId)->year ?? '-')) }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <p class="font-semibold text-xl text-center py-3"> {{ __('Detail Penilaian Sikap untuk ' . $classSubject->subject->subject_name)}} </p>

                    {{-- Data Diri Singkat --}}
                    <div class="mb-4 flex items-start justify-between">
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
                    </div>
                    {{-- End - Data Diri Singkat --}}

                    {{-- Isi Tabel --}}
                    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                        <x-table header="Header Content" class="overflow-x-auto mx-auto">
                            <x-slot name="header">
                                <tr>
                                    <th>No</th>
                                    <th>Tipe Penilaian</th>
                                    <th>Nilai</th>
                                    <th>Grade</th>
                                    <th>Nilai Akhir</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </x-slot>
                            @php $num = 1; @endphp
                            @foreach($assessmentTypes as $assessmentType)
                            @php
                                $attitudeScore = $attitudeScores->firstWhere('assessment_type', $assessmentType) ?? (object)['score' => 0, 'grade' => '-', 'final_score' => 0, 'description' => 'Tidak Ada Deskripsi'];
                            @endphp
                             <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td>{{ $assessmentType ?? '-'}}</td>
                                <td class="text-center">{{ $attitudeScore->score ?? '0'}}</td>
                                <td class="text-center">{{ $attitudeScore->grade ?? '-'}}</td>
                                <td class="text-center">{{ $attitudeScore->final_score ?? '0'}}</td>
                                <td>{{ $attitudeScore->description ?? 'Tidak ada deskripsi'}}</td>
                                <td class="text-center">
                                    <x-edit-primary-button tag="a" href="{{ route('grade.editAttitudeScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'assessmentType' => $assessmentType, 'semesterYearId' => $selectedSemesterYearId]) }}"
                                        class="flex items-center justify-center min-w-[60px]">
                                        <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                        <span class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                    </x-edit-primary-button>
                                </td>
                            </tr>
                            @endforeach
                        </x-table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


