<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rapor Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if($rapors->count() > 0)
                    <div class="mb-4">
                        <p class="font-medium">Data Diri Singkat</p>
                        <p>Nama: {{ $student->student_name }}</p>
                        <p>NIS: {{ $student->nis }}</p>
                        <p>Nama Sekolah: {{ $rapors->first()->school_name ?? 'SDN DAWUAN' }}</p>
                        <p>Alamat Sekolah: {{ $rapors->first()->school_address ?? 'KP Pasir Eurih' }}</p>
                        <p>Kelas: {{ optional($student->class)->level ?? 'N/A' }}</p>
                        <p>
                            Semester:
                            <form action="{{ route('rapors.index', ['id' => $student->id]) }}" method="GET">
                                <select name="semester_year_id" id="semester_year_id" onchange="this.form.submit();">
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}" {{ $semester->id == $selectedSemesterYearId ? 'selected' : '' }}>
                                            Semester {{ $semester->semester }} Tahun {{ $semester->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </p>
                    </div>

                    <!-- Tabel Nilai Mata Pelajaran -->
                    <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Predikat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KKM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Keterampilan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $num = 1; @endphp
                                @foreach($rapors->groupBy('grade.classSubject.subject.id') as $subjectRapors)
                                    @php
                                        $firstRapor = $subjectRapors->first();
                                        $subject = optional($firstRapor->grade->classSubject->subject)->subject_name ?? 'N/A';
                                        $averageKnowledgeScore = $firstRapor->grade->average_knowledge_score ?? 'N/A';
                                        $averageSkillScore = $firstRapor->grade->average_skill_score ?? 'N/A';
                                        $gradeKnowledge = $firstRapor->grade->gradeKnowledge ?? 'N/A'; // Menggunakan gradeKnowledge yang telah disimpan
                                        $kkm = optional($firstRapor->grade->classSubject->subject)->kkm ?? 'N/A';
                                    @endphp
                                    <tr class="text-center">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $num++ }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $subject }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $averageSkillScore }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $gradeKnowledge }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $kkm }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $averageKnowledgeScore }}</td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- Tambahkan tombol-tombol aksi di sini --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                @else
                    <p class="text-red-500">Data rapor tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
