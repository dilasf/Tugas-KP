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
                    {{-- @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif --}}

                    <!-- Section for Waiting Validation Reports -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Rapor yang Menunggu Validasi</h3>
                        <div class="text-black max-h-[calc(100vh-400px)] overflow-y-auto">
                            <x-table header="Header Content" class="overflow-x-auto mx-auto">
                                <x-slot name="header">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Semester</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </x-slot>
                                @php $num=1; @endphp
                                @forelse ($waitingReports as $rapor)
                                    <tr>
                                        <td class="text-center">{{ $num++ }}</td>
                                        <td>{{ ucwords(strtolower($rapor->grade->student->student_name))}}</td>
                                        <td class="text-center">{{ $rapor->grade->classSubject->class->class_name }}</td>
                                        <td class="text-center">{{ $rapor->grade->semesterYear->semester }}</td>
                                        <td class="text-center">
                                            @if ($rapor->status == 'waiting_validation')
                                                <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM4.285 12.745a.682.682 0 1 1-.97-.97L7.03 8.586 6.15 7.465a.682.682 0 1 1 .97-.97l1.315 1.515c.174.201.269.472.269.754 0 .281-.095.553-.27.754l-4.85 5.226z"></path>
                                                    </svg>
                                                    Menunggu
                                                </span>
                                            @else
                                                {{ $rapor->status }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-primary-button tag="a" href="{{ route('rapors.index', ['studentId' => $rapor->grade->student->id, 'semester_year_id' => $rapor->grade->semesterYear->id]) }}"
                                                class="flex items-center justify-center min-w-[60px] max-h-[31px]">
                                                <img src="{{ asset('img/detail_logo.png') }}" class="w-[10px] h-[13px]">
                                                <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Lihat Rapor') }}</span>
                                            </x-primary-button>

                                            <form action="{{ route('rapors.validation.approve', ['id' => $rapor->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 ml-4">Validasi</button>
                                            </form>
                                            <form action="{{ route('rapors.validation.reject', ['id' => $rapor->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Tolak</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Tidak ada rapor yang menunggu validasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
