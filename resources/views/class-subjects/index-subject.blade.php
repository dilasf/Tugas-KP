
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Daftar Mata Pelajaran dan Kelas') }}
        </h2>
    </x-slot>
    <div class="bg-white rounded-lg shadow-md mx-4 overflow-hidden p-4">
        @foreach($classes as $class)
            <div class="border-b border-gray-300 pb-4">
                <div class="mt-4 bg-gray-100 p-4 rounded-md shadow-sm">
                    <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 px-2">
                        {{ 'Dari '.$class->class_name }}
                    </h3>
                </div>
                <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto mt-4">
                    <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                        <x-slot name="header">
                            <tr>
                                <th>Nama Pelajaran</th>
                                <th>Aksi</th>
                            </tr>
                        </x-slot>
                        @foreach($classSubjects->where('class_id', $class->id) as $classSubject)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $classSubject->subject->subject_name }}</td>
                                <td class="text-center px-4 py-2">
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
        @endforeach
    </div>
</x-app-layout>
