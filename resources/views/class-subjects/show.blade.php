<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 py-4">
            <a href="{{ route('class-subjects.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] cursor-pointer">
            </a>
            <p class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Penilaian ' . ucwords($classSubject->class->class_name)) }}
            </p>
        </div>
    </x-slot>
    <div class="bg-white rounded-lg shadow-md mx-4 my-4 p-4 min-w-[300px] min-h-[450px]">
        <p class="font-semibold text-xl text-center py-3"> {{ __('Daftar Siswa Untuk ' . $classSubject->subject->subject_name)}} </p>

        <div class="text-black overflow-y-auto py-4">
            <x-table class="overflow-x-auto mx-auto">
                <x-slot name="header">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">NIS</th>
                        <th class="px-4 py-2">Nama Siswa</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </x-slot>
                @php $num=1; @endphp
                @foreach($students as $siswa)
                <tr class="border-b">
                    <td class="text-center px-4 py-2">{{ $num++ }}</td>
                    <td class="text-center px-4 py-2">{{ $siswa->nis }}</td>
                    <td class="px-4 py-2">{{ ucwords(strtolower($siswa->student_name)) }}</td>
                    <td class="text-center px-4 py-2">
                        <x-edit-primary-button tag="a" href="{{ route('grade.index', ['studentId' => $siswa->id, 'classSubjectId' => $classSubject->id]) }}"
                            class="flex items-center justify-center min-w-[60px]">
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
