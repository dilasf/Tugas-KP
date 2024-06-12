<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('class-subjects.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <p class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Daftar Siswa untuk ' . $classSubject->subject->subject_name . ' di ' . $classSubject->class->class_name) }}
            </p>
        </h2>
        </div>
    </x-slot>
    <div class="bg-white rounded-lg shadow-md mx-4 py-6 overflow-hidden">
        <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
            <x-table class="overflow-x-auto mx-auto">
                <x-slot name="header">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Aksi</th>
                    </tr>
                </x-slot>
                @php $num=1; @endphp
                @foreach($students as $student)
                <tr>
                    <td  class="text-center">{{ $num++ }}</td>
                        <td  class="text-center">{{ $student->nis }}</td>
                        <td>{{ $student->student_name }}</td>
                        <td  class="text-center">
                           <a href="{{ route('grade.index', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id]) }}"
                                class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 md:px-4 md:py-2 bg-light-blue border border-transparent rounded-md font-semibold
                                       text-xs sm:text-xs md:text-sm text-white dark:text-gray-800 tracking-widest
                                       hover:bg-sky-600 dark:hover:bg-white focus:bg-sky-600  active:bg-sky-600
                                       focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2
                                       transition ease-in-out duration-150">
                                 <span class="text-12px ml-1">{{ __('Penilaian') }}</span>
                             </a>

                        </td>

                    </tr>
                @endforeach
            </x-table>
        </div>
    </div>
</x-app-layout>
