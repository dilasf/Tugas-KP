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

                    <p class="font-semibold text-xl text-center py-3"> {{ __('Detail Penilaian Keterampilan untuk ' . $classSubject->subject->subject_name)}} </p>

                    {{-- Data Diri Singkat --}}
                    <div class="mb-4 flex items-start justify-between">
                        <div>
                            <div class="mb-2 flex items-center">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Nama Siswa</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-800 text-16px inline-block">{{ $student->student_name ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-2 flex items-center">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">NIS</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-800 text-16px inline-block">{{ $student->nis ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- End - Data Diri Singkat --}}

                    {{-- Isi Tabel Keterampilan --}}
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
                                $skillScore = $skillScores->firstWhere('assessment_type', $assessmentType) ?? (object)['score' => 0, 'grade' => '-', 'final_score' => 0, 'description' => 'Tidak Ada Deskripsi'];
                            @endphp
                            <tr>
                                <td class="text-center">{{ $num++ }}</td>
                                <td>{{ $assessmentType ?? '-'}}</td>
                                <td class="text-center">{{ $skillScore->score ?? '0'}}</td>
                                <td class="text-center">{{ $skillScore->grade ?? '-'}}</td>
                                <td class="text-center">{{ $skillScore->final_score ?? '0'}}</td>
                                <td>{{ $skillScore->description ?? 'Tidak ada deskripsi'}}</td>
                                <td class="text-center">
                                    <x-edit-primary-button tag="a" href="{{ route('grade.editSkillScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'semesterYearId' => $selectedSemesterYearId, 'assessmentType' => $assessmentType]) }}"
                                        class="flex items-center justify-center min-w-[60px]">
                                        <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                        <span class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                    </x-edit-primary-button>
                                </td>
                            </tr>
                            @endforeach
                        </x-table>
                    </div>
                    {{-- End - Isi Tabel Keterampilan --}}

                    {{-- Tabel Absensi --}}
                    <div class="mt-8">
                        <p class="font-semibold text-xl text-center py-3"> {{ __('Detail Absensi')}} </p>
                        <x-table class="overflow-x-auto mx-auto">
                            <x-slot name="header">
                                <tr>
                                    <th>No</th>
                                    <th>Absensi</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </x-slot>
                            @php
                                $attendances = [
                                    'sick' => 'Sakit',
                                    'permission' => 'Ijin',
                                    'unexcused' => 'Apha'
                                ];
                                $num = 1;
                                $attendance = $attendance ?? (object)['sick' => 0, 'permission' => 0, 'unexcused' => 0];
                            @endphp
                            @foreach($attendances as $type => $label)
                                <tr>
                                    <td class="text-center">{{ $num++ }}</td>
                                    <td>{{ $label }}</td>
                                    <td class="text-center" id="{{ $type }}-count">{{ $attendance->$type }}</td>
                                    <td class="flex flex-col items-center gap-2 text-center">
                                        <div class="flex flex-row gap-2" style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
                                            <form class="update-attendance-form" method="POST" action="{{ route('attendance.update', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'semesterYearId' => $selectedSemesterYearId, 'type' => $type, 'action' => 'increment']) }}" style="margin: 0 5px;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded increment-btn" data-type="{{ $type }}" data-action="increment">+</button>
                                            </form>
                                            <form class="update-attendance-form" method="POST" action="{{ route('attendance.update', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'semesterYearId' => $selectedSemesterYearId, 'type' => $type, 'action' => 'decrement']) }}" style="margin: 0 5px;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded decrement-btn" data-type="{{ $type }}" data-action="decrement">-</button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </x-table>
                    </div>
                    {{-- End - Tabel Absensi --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.update-attendance-form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    var formData = new FormData(form);
                    var type = form.querySelector('button').getAttribute('data-type');
                    var action = form.querySelector('button').getAttribute('data-action');
                    var countElement = document.getElementById(type + '-count');

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': formData.get('_token'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            countElement.innerText = data.new_value;
                        } else {
                            console.error('Failed to update attendance.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        });
    </script>
</x-app-layout>
