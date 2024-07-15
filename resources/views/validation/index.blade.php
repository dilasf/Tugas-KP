<!-- resources/views/rapors/validation/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rapor yang Menunggu Validasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($waitingReports as $rapor)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $rapor->grade->student->student_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $rapor->grade->classSubject->class->class_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $rapor->grade->semesterYear->semester }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $rapor->status }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- <a href="{{ route('rapors.validation.detail', ['id' => $rapor->id]) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a> --}}
                                        <x-primary-button tag="a" href="{{ route('rapors.index', ['studentId' => $rapor->grade->student->id]) }}"
                                            class="flex items-center justify-center min-w-[60px] max-h-[31px]">
                                            <img src="{{ asset('img/detail_logo.png') }}" class="w-[10px] h-[13px]">
                                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Lihat Rapor') }}</span>
                                        </x-primary-button>
                                        <form action="{{ route('rapors.validation.approve', ['id' => $rapor->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 ml-4">Validasi</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        Tidak ada rapor yang menunggu validasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-bold mb-4">{{ $rapor->grade->student->student_name }}</h3>
                    <p><strong>Kelas:</strong> {{ $rapor->grade->classSubject->class->class_name }}</p>
                    <p><strong>Semester:</strong> {{ $rapor->grade->semesterYear->semester }}</p>
                    <p><strong>Status:</strong> {{ $rapor->status }}</p>
                    <p><strong>Social Attitudes:</strong> {{ $rapor->social_attitudes }}</p>
                    <p><strong>Spiritual Attitude:</strong> {{ $rapor->spiritual_attitude }}</p>
                    <p><strong>Suggestion:</strong> {{ $rapor->suggestion }}</p>

                    <form action="{{ route('rapors.validation.approve', ['id' => $rapor->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-4">Validasi</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
