<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

    <div class="flex-grow bg-white rounded-lg shadow-md mx-6 my-1 p-10 min-h-[calc(70vh-5px)]">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 my-4 gap-10">

            @role('admin|kepala_sekolah')
                <div style="width: 259px;">
                    <x-box-purple tag="button" href="{{ route('teacher_data.index') }}" :count="$teacherCount" title="Data Guru"/>
                </div>

                <div style="width: 259px;">
                    <x-box-aqua tag="button" href="{{ route('student_data.index') }}" :count="$studentCount" title="Data Siswa"/>
                </div>
            @endrole

            @role('kepala_sekolah')
            <div class="bg-gradient-to-r from-blue-500 to-teal-500 text-white shadow-lg rounded-lg p-6 hover:from-teal-500 hover:to-blue-500 transition duration-300 ease-in-out">
                <p class="text-3xl"> {{ $pendingReportsCount }}</p>
                <h2 class="text-lg font-medium">Rapor Menunggu Validasi</h2>
                <a href="{{ route('rapors.validation.index') }}" class="text-white underline mt-2 block">Lihat Detail</a>
            </div>
            @endrole

            @role('admin')
                <div style="width: 259px;">
                    <x-box-blue tag="button" data-hs-overlay="#hs-cookies" :count="$accountsCount" title="Akun"/>
                </div>

                <div style="width: 259px;">
                    <x-box-green tag="button" href="{{ route('subject.index') }}" :count="$subjectCount" title="Mata Pelajaran"/>
                </div>

                <div style="width: 259px;">
                    <x-box-orange tag="button" href="{{ route('class.index') }}" :count="$classCount" title="Kelas"/>
                </div>

                <div style="width: 259px;">
                    <x-box-purple tag="button" href="{{ route('subject.semester_year.index') }}" :count="$semesterYearCount" title="Data Semester & TA"/>
                </div>
            @endrole

            @role('guru_kelas|guru_mapel')

            <div style="width: 259px;">
                <x-box-blue tag="button" href="{{ route('grade.knowledge_scores.index') }}" :count="$uniqueKnowledge" title="Data Nilai Pengetahuan"/>
            </div>

            <div style="width: 259px;">
                <x-box-green tag="button" href="{{ route('grade.skill_scores.index') }}" :count="$uniqueSkill" title="Data Nilai Keterampilan"/>
            </div>

            <div style="width: 259px;">
                <x-box-orange tag="button" href="{{ route('grade.attitude_scores.index') }}" :count="$uniqueAttitude" title="Data Nilai Sikap"/>
            </div>

        @endrole



        {{-- <div style="width: 259px;">
            <x-box-orange tag="button" href="{{ route('rapors.validation.index') }}" :count="$uniqueAttitude" title="Data Nilai Sikap"/>
        </div> --}}

            @if (Auth::user()->role_id == 5) <!-- Role ID 5 adalah siswa -->
                <div class="col-span-1 lg:col-span-3">
                    {{-- Data Diri --}}
                    <div class="mb-4">
                        <p class="font-semibold text-xl text-center">RAPOR DAN PROFIL PESERTA DIDIK</p>
                        <div class="flex justify-between">
                            <div class="py-10">
                                <div class="mb-2 flex items-center">
                                    <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">Nama Peserta Didik</p>
                                    <p class="text-gray-800 text-16px mr-2">:</p>
                                    <p class="text-gray-900 font-medium text-16px">{{ strtoupper(Auth::user()->name ?? 'N/A') }}</p>
                                </div>
                                <div class="mb-2 flex items-center">
                                    <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">NIS</p>
                                    <p class="text-gray-800 text-16px mr-2">:</p>
                                    <p class="text-gray-900 font-medium text-16px">{{ Auth::user()->student->nis ?? 'N/A' }}</p>
                                </div>
                                <div class="mb-2 flex items-center">
                                    <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">Nama Sekolah</p>
                                    <p class="text-gray-800 text-16px mr-2">:</p>
                                    <p class="text-gray-900 font-medium text-16px">{{ strtoupper($validatedReports->first()->school_name ?? 'SDN DAWUAN') }}</p>
                                </div>
                                <div class="mb-2 flex items-center">
                                    <p class="font-medium text-16px text-gray-800 mr-2 w-[200px]">Alamat Sekolah</p>
                                    <p class="text-gray-800 text-16px mr-2">:</p>
                                    <p class="text-gray-900 font-medium text-16px">{{ $validatedReports->first()->school_address ?? 'KP Pasir Eurih' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- END Data Diri --}}

                    <h3 class="text-xl font-semibold mb-4">Rapor yang Telah Divalidasi</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach($validatedReports as $rapor)
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <p class="font-semibold">{{ $rapor->grade->classSubject->class->name }} - Semester {{ $rapor->grade->semesterYear->semester }}</p>
                            <p class="text-sm text-gray-600">Tahun: {{ $rapor->grade->semesterYear->year }}</p>
                            <p class="text-sm text-gray-600"><strong>Status:</strong> {{ $rapor->status }}</p>
                            <a  href="{{ route('rapors.index', ['studentId' => $rapor->grade->student->id]) }}" class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                                Lihat Detail
                            </a>
                            <x-primary-button tag="a" href="{{ route('rapors.index', ['studentId' => $rapor->grade->student->id]) }}"
                                class="flex items-center justify-center min-w-[60px] max-h-[31px]">
                                <img src="{{ asset('img/detail_logo.png') }}" class="w-[10px] h-[13px]">
                                <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Lihat Rapor') }}</span>
                            </x-primary-button>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif


    <div id="hs-cookies" class="hs-overlay hidden fixed top-0 left-0 z-50 w-full h-full bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8 text-center relative">
            <button type="button" class="absolute top-2 right-2 p-2 rounded-full bg-gray-200 text-gray-800 hover:bg-gray-300 focus:outline-none" data-hs-overlay="#hs-cookies">
                <span class="sr-only">Close</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M14.293 5.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 1.414L8.586 10 4.293 5.707a1 1 0 111.414-1.414L10 8.586l4.293-4.293z" clip-rule="evenodd"></path>
                </svg>
            </button>

            <div class="flex justify-center mt-4">
                <svg class="w-16 h-16" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="14" cy="14" r="12" stroke="black" stroke-width="2" fill="transparent"/>
                    <path d="M14 16C16.7614 16 19 13.7614 19 11C19 8.23858 16.7614 6 14 6C11.2386 6 9 8.23858 9 11C9 13.7614 11.2386 16 14 16Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 23C5 19 14 18 14 18C14 18 23 19 23 23" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <h3 class="text-2xl font-bold text-gray-800 mt-4">
                Pilih Akun Anda
            </h3>
            <p class="text-gray-500 mt-2">
                Silakan pilih jenis akun yang ingin Anda kelola.
            </p>

            <div class="flex justify-center mt-6 space-x-4">
                <a href="{{ route('account.teacher.index') }}" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-gray-200 text-gray-800 hover:bg-gray-300 focus:outline-none">
                    Akun Guru
                </a>
                <a href="{{ route('account.student.index') }}" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-semi-blue text-white hover:bg-blue-700 focus:outline-none">
                    Akun Siswa
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.querySelector('[data-hs-overlay="#hs-cookies"]');
            const modal = document.querySelector('#hs-cookies');
            const closeButton = modal.querySelector('[data-hs-overlay="#hs-cookies"]');

            button.addEventListener('click', function () {
                modal.classList.toggle('hidden');
            });

            closeButton.addEventListener('click', function () {
                modal.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>
