<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

    <div class="flex-grow rounded-lg mx-6 my-1 p-10 min-h-[calc(70vh-5px)]">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 my-4 gap-10">
            @role('admin|kepala_sekolah')
                <x-dashboard-card color="purple" route="teacher_data.index" count="{{ $teacherCount }}" title="Data Guru" icon="teacher" />
                <x-dashboard-card color="aqua" route="student_data.index" count="{{ $studentCount }}" title="Data Siswa" icon="student" />
            @endrole

            @role('kepala_sekolah')
                <x-dashboard-card color="blue" route="rapors.validation.index" count="{{ $pendingReportsCount }}" title="Rapor Menunggu Validasi" icon="reports" />
            @endrole

            @role('admin')
                <!-- Card to trigger modal -->
                <x-dashboard-card color="blue" route="#" count="{{ $accountsCount }}" title="Akun" :dataModalTrigger="true" modalTarget="#hs-account-choice" />

                <x-dashboard-card color="green" route="subject.index" count="{{ $subjectCount }}" title="Mata Pelajaran" icon="subjects" />
                <x-dashboard-card color="orange" route="class.index" count="{{ $classCount }}" title="Kelas" icon="classes" />
                <x-dashboard-card color="purple" route="subject.semester_year.index" count="{{ $semesterYearCount }}" title="Data Semester & TA" icon="semester" />
            @endrole

            @role('guru_kelas|guru_mapel')
                <x-dashboard-card color="green" route="grade.skill_scores.index" count="{{ $uniqueSkill }}" title="Data Nilai Keterampilan" icon="default" />
                <x-dashboard-card color="blue" route="grade.knowledge_scores.index" count="{{ $uniqueKnowledge }}" title="Data Nilai Pengetahuan" icon="default" />
                <x-dashboard-card color="orange" route="grade.attitude_scores.index" count="{{ $uniqueAttitude }}" title="Data Nilai Sikap" icon="default" />
            @endrole

           {{-- Beranda Siswa --}}
            @if (Auth::user()->role_id == 5) <!-- Role ID 5 adalah siswa -->
            <div class="col-span-1 lg:col-span-3">
                <!-- Data Diri -->
                <div class="mb-6 bg-white shadow rounded-lg p-6">
                    <p class="font-semibold text-xl text-center mb-4 text-gray-800">RAPOR DAN PROFIL PESERTA DIDIK</p>
                    <!-- Centered Photo and Name -->
                    <div class="flex flex-col items-center mb-6">
                        <img id="student-photo" class="object-contain mb-3 rounded-full h-32 w-24" src="{{ Auth::user()->student_photo ? asset('storage/photos/' . Auth::user()->student_photo) : asset('img/profil.png') }}" alt="Student Photo">
                        <p class="font-semibold uppercase text-gray-900 text-lg">{{ strtoupper(Auth::user()->name) }}</p>
                    </div>
                    <!-- Student Information in Two Columns -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div>
                            <div class="mb-3 flex items-center">
                                <p class="font-medium text-gray-700 mr-2 w-[120px]">NISN</p>
                                <p class="text-gray-700 mr-2">:</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->student->nisn ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3 flex items-center">
                                <p class="font-medium text-gray-700 mr-2 w-[120px]">NIS</p>
                                <p class="text-gray-700 mr-2">:</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->student->nis ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3 flex items-center">
                                <p class="font-medium text-gray-700 mr-2 w-[120px]">Jenis Kelamin</p>
                                <p class="text-gray-700 mr-2">:</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->student->gender ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3 flex items-center">
                                <p class="font-medium text-gray-700 mr-2 w-[120px]">Tanggal Lahir</p>
                                <p class="text-gray-700 mr-2">:</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->student->date_of_birth ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div>
                            <div class="mb-3 flex items-center">
                                <p class="font-medium text-gray-700 mr-2 w-[120px]">Agama</p>
                                <p class="text-gray-700 mr-2">:</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->student->religion ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <div class="flex items-start">
                                    <p class="font-medium text-gray-700 mr-2 w-[120px]">Alamat</p>
                                    <p class="text-gray-700 mr-2">:</p>
                                    <div class="text-gray-900">
                                        @php
                                        $address = Auth::user()->student->address ?? 'N/A';
                                        $address_lines = explode("\n", wordwrap($address, 35, "\n", false));
                                        @endphp
                                        @foreach($address_lines as $line)
                                        <p>{{ $line }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 flex items-center">
                                <p class="font-medium text-gray-700 mr-2 w-[120px]">Nama Sekolah</p>
                                <p class="text-gray-700 mr-2">:</p>
                                <p class="text-gray-900 font-medium">{{ strtoupper($validatedReports->first()->school_name ?? 'SDN DAWUAN') }}</p>
                            </div>
                            <div class="mb-3 flex items-center">
                                <p class="font-medium text-gray-700 mr-2 w-[120px]">Alamat Sekolah</p>
                                <p class="text-gray-700 mr-2">:</p>
                                <p class="text-gray-900 font-medium">{{ $validatedReports->first()->school_address ?? 'KP Pasir Eurih' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Data Diri -->

                <h3 class="text-xl font-semibold mb-6 text-gray-800">Rapor yang Telah Divalidasi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($validatedReports as $rapor)
                    <div class="bg-white p-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                        <p class="font-semibold text-gray-800">{{ $rapor->grade->classSubject->class->name }} - Semester {{ $rapor->grade->semesterYear->semester }}</p>
                        <p class="text-sm text-gray-500">Tahun: {{ $rapor->grade->semesterYear->year }}</p>
                        <p class="text-sm text-gray-500"><strong>Status:</strong> {{ $rapor->status }}</p>
                        <a href="{{ route('rapors.index', ['studentId' => $rapor->grade->student->id]) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-full font-semibold text-sm text-white tracking-wide hover:bg-blue-500 transition duration-150 ease-in-out">
                            Lihat Detail
                        </a>
                        <a href="{{ route('rapors.download', ['studentId' => $rapor->grade->student->id]) }}" class="text-blue-500 underline">Download PDF</a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif


        </div>
    </div>



  <!-- Modal -->
  <div id="hs-account-choice" class="hs-overlay hidden fixed inset-0 z-[80] flex items-center justify-center pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-bg-gray-on-hover-cards-label">
    <div class="hs-overlay-open:mt-5 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all max-w-sm w-full mx-auto my-10 bg-white border border-gray-200 shadow-lg rounded-lg pointer-events-auto">
      <!-- Header Modal -->
      <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
        <button type="button" class="w-8 h-8 inline-flex justify-center items-center rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200" aria-label="Close" data-hs-overlay="#hs-account-choice">
          <span class="sr-only">Close</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Isi Modal -->
      <div class="p-6 overflow-y-auto text-center">
        <div class="flex justify-center mt-4">
          <!-- Ikon yang Diimpor -->
          <img src="img/icons8-account-96.png" class="w-16 h-16 filter invert" alt="Account Icon" />
        </div>

        <h3 class="text-xl font-semibold text-gray-900 mt-4">
          Pilih Akun Anda
        </h3>
        <p class="text-gray-600 mt-2">
          Silakan pilih jenis akun yang ingin Anda kelola.
        </p>

        <div class="flex flex-col space-y-3 mt-6">
          <a href="{{ route('account.teacher.index') }}" class="flex items-center justify-center py-2 px-4 text-sm font-semibold rounded-md border border-gray-300 bg-gray-200 text-gray-800 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Akun Guru
          </a>
          <a href="{{ route('account.student.index') }}" class="flex items-center justify-center py-2 px-4 text-sm font-semibold rounded-md border border-transparent bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Akun Siswa
          </a>
        </div>
      </div>
    </div>
  </div>




</x-app-layout>
