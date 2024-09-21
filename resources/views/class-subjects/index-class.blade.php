<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Daftar Mata Pelajaran dan Kelas') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-lg shadow-lg mx-4 my-6 p-6 min-w-[300px] min-h-[450px]">
            <div class="border-b border-gray-200 pb-4 mb-4">
                <div class="bg-gray-100 p-4 rounded-lg shadow-md mb-4">
                    <h2 class="font-semibold text-xl text-gray-800 px-2">
                        {{ 'Mata Pelajaran yang Diajarkan: ' . $teacher->teaching }}
                    </h2>
                </div>
        <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
            <x-table :sidebarOpen="$sidebarOpen" class="overflow-x-auto border-none">
                <x-slot name="header">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Nama Kelas</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </x-slot>
                @php $num = 1; @endphp
                @foreach($classSubjects as $classSubject)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $num++ }}</td>
                        <td class="px-4 py-2">{{ $classSubject->class->class_name }}</td>
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
 </div>
</x-app-layout>
