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
                @foreach($students as $siswa)
                <tr>
                    <td  class="text-center">{{ $num++ }}</td>
                        <td  class="text-center">{{ $siswa->nis }}</td>
                        <td>{{ ucwords(strtolower($siswa->student_name))}}</td>
                        <td :class="{ 'flex flex-col items-center justify-center gap-2': sidebarOpen, 'flex justify-center items-center gap-2': !sidebarOpen }">
                            <x-edit-primary-button tag="a" href="{{ route('grade.index', ['studentId' => $siswa->id, 'classSubjectId' => $classSubject->id]) }}"
                                class="flex items-center justify-center min-w-[60px] ">
                                <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Penilaian') }}</span>
                            </x-edit-primary-button>

                        </td>

                    </tr>
                @endforeach
            </x-table>
        </div>
    </div>
</x-app-layout>
