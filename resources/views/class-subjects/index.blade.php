<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Mata Pelajaran dan Kelas') }}
        </h2>
    </x-slot>
    <div class="bg-white rounded-lg shadow-md mx-4 overflow-hidden">
        <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
            <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                <x-slot name="header">
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Nama Pelajaran</th>
                        <th>Aksi</th>
                    </tr>
                </x-slot>
                @foreach($classSubjects as $classSubject)
                    <tr>
                        <td>{{ $classSubject->class->class_name }}</td>
                        <td>{{ $classSubject->subject->subject_name }}</td>
                        <td class="text-center">
                            <x-detail-primary-button tag="a" href="{{ route('class-subjects.show', $classSubject->id) }}"
                                class="flex items-center justify-center min-w-[60px]">
                                <img src="{{ asset('img/detail_logo.png') }}" class="w-[13px] h-[13px]">
                                <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Detail') }}</span>
                            </x-detail-primary-button>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </div>
    </div>
</x-app-layout>
