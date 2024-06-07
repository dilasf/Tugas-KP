<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('grade.index', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'semesterYearId' => $semesterYearId]) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <p class="font-semibold text-gray-800 leading-tight text-2xl">
                {{ __('Detail Penilaian Pengetahuan untuk ' . $classSubject->subject->subject_name . ' di ' . $classSubject->class->class_name) }}
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

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
                        <div class="mb-2 flex items-center">
                            <p class="font-medium text-16px mr-2 inline-block {{ $sidebarOpen ? 'w-[100px]' : 'w-[147px]' }}">Semester</p>
                            <p class="text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-800 text-16px inline-block">{{ 'Semester ' . $semester->semester . ' Tahun ' . $semester->year ?? 'N/A' }}</p>
                        </div>
                    </div>
                {{-- End - Data Diri Singkat --}}

                {{-- Isi Tabel --}}
                <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                    <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                        <x-slot name="header">
                                <tr>
                                    <th >No</th>
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
                                    // Cari KnowledgeScore berdasarkan assessment_type
                                    $knowledgeScore = $knowledgeScores->firstWhere('assessment_type', $assessmentType);
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $num++ }}</td>
                                    <td>{{ $assessmentType }}</td>
                                    <td class="text-center">{{ $knowledgeScore->score ?? '0' }}</td>
                                    <td class="text-center">{{ $knowledgeScore->grade ?? '-' }}</td>
                                    <td class="text-center">{{ $knowledgeScore->final_score ?? '0' }}</td>
                                    <td>{{ $knowledgeScore->description ?? 'Tidak Ada Deskripsi' }}</td>
                                    <td class="text-center">
                                        <x-edit-primary-button tag="a" href="{{ route('grade.editKnowledgeScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'semesterYearId' => $semesterYearId, 'assessmentType' => $assessmentType]) }}"
                                            class="flex items-center justify-center min-w-[60px]">
                                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
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
