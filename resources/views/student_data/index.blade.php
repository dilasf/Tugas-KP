<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Siswa') }}
        </h2>
    </x-slot>
    <div class="bg-white rounded-lg shadow-md mx-4 overflow-hidden">

        {{-- Fitur atas --}}
        <div class="flex justify-between items-center px-6 py-4">
            <div style="order: 1;">
                <x-search-box>
                    <input id="searchInput" class="py-3 px-4 w-full border-gray-200 rounded-lg text-sm focus:border-light-blue focus:ring-light-blue disabled:opacity-50 disabled:pointer-events-none" type="text" placeholder="Cari siswa " value="">
                </x-search-box>
            </div>
            <div style="order: 2;">
                <div class="flex items-center">
                    <x-edit-primary-button x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'import-student')" class="mr-4 font-semibold inline-flex items-center"
                        style="padding: 0.5rem 1rem;">
                        <img src="{{ asset('img/import_logo.png') }}" class="w-[18px] h-[18px]"  alt="Import Icon">
                        <span class="text-12px ml-1">{{ __('Import Excel') }}</span>
                    </x-edit-primary-button>
                    <x-primary-button tag="a" href="{{route('student_data.create')}}" class="font-semibold inline-flex items-center"
                        style="padding: 0.5rem 1rem;">
                        <span class="text-12px ml-1">{{ __('+ Tambah Data Siswa') }}</span>
                    </x-primary-button>
                </div>
            </div>
        </div>
        {{-- End Fitur atas --}}

        {{-- Data Siswa Singkat --}}
        <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
            <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                <x-slot name="header">
                    <tr>
                        <th>No</th>
                        <th>Photo</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>NIPD</th>
                        <th>Nama Kelas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </x-slot>
                @php $num=1; @endphp
                @foreach($students as $siswa)
                <tr class="text-center">
                    <td>{{ $num++ }}</td>
                    <td>

                        @if ($siswa->student_photo)
                            <img src="{{ asset('storage/photos/'.$siswa->student_photo) }}" alt="{{ $siswa->student_name }}" class="w-16 h-auto mb-1">
                        @else
                            <img src="{{ asset('img/profil.png') }}" alt="No photo" class="w-[50px] h-auto mb-1">
                        @endif
                </td>
                    <td>{{ $siswa->nis }}</td>
                    <td>{{ ucwords(strtolower($siswa->student_name))}}</td>
                    <td>{{ $siswa->gender === 'Perempuan' ? 'P' : 'L' }}</td>
                    <td>{{ $siswa->nipd }}</td>
                    <td>{{ $siswa->class->class_name }}</td>
                    <td class="size-px whitespace-nowrap">
                        <div class="px-6 py-3">
                            @if($siswa->status == 1)
                                <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full dark:bg-green-500/10 dark:text-green-500">
                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </td>
                    <td :class="{ 'flex flex-col items-center gap-2': sidebarOpen, 'justify-center items-center gap-2': !sidebarOpen }">
                        <x-detail-primary-button tag="a" href="{{ route('student_data.show-detail', ['id' => $siswa->id]) }}"
                            class="flex items-center justify-center min-w-[60px]">
                            <img src="{{ asset('img/detail_logo.png') }}" class="w-[13px] h-[13px]">
                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Detail') }}</span>
                        </x-detail-primary-button>

                        <x-edit-primary-button tag="a" href="{{ route('student_data.edit', ['id' => $siswa->id]) }}"
                            class="flex items-center justify-center min-w-[60px]">
                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                        </x-edit-primary-button>

                        <a href="{{ route('rapors.index', ['studentId' => $siswa->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Lihat Rapor
                        </a>

                        <x-danger-button
                            x-data=""
                            x-on:click.prevent="
                                $dispatch('open-modal', 'confirm-student-deletion');
                                $dispatch('set-action', '{{ route('student_data.destroy', $siswa->id) }}');"
                            class="flex items-center justify-center min-w-[60px]">
                            <img src="{{ asset('img/garbage_logo.png') }}" class="w-[13px] h-[13px]">
                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Hapus') }}</span>
                        </x-danger-button>
                    </td>

                </tr>
                @endforeach
            </x-table>
            {{-- End Data Siswa Singkat --}}

            {{-- Modal Hapus --}}
            <x-modal name="confirm-student-deletion" focusable maxWidth="xl">
                <form method="post" x-bind:action="action" class="p-6 flex items-center">
                    @csrf
                    @method('delete')

                    <div class="flex items-start">
                        <!-- Icon -->
                        <span class="flex-shrink-0 inline-flex justify-center items-center size-[46px] sm:w-[62px] sm:h-[62px] rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100 mr-6">
                            <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </span>
                        <!-- End Icon -->

                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Apakah anda yakin akan menghapus data?') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Setelah proses dilaksanakan. Data akan dihilangkan secara permanen.') }}
                            </p>
                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Batal') }}
                                </x-secondary-button>
                                <x-danger-button class="ml-3">
                                    {{ __('Hapus!!!') }}
                                </x-danger-button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-modal>
            {{-- End Modal Hapus --}}

            {{-- Modal Impor --}}
            <x-modal name="import-student" focusable maxWidth="xl">
                <form method="post" action="{{ route('student_data.import') }}"
                   class="p-6" enctype="multipart/form-data">
                   @csrf
                   <h2 class="text-lg font-medium text-gray-900">
                      {{ __('Import Data SIswa') }}
                   </h2>
                   <div class="max-w-xl">
                      <x-input-label for="cover" class="sr-only" value="File
                         Import"/>
                      <x-file-input id="cover" name="file" class="mt-1 block wfull" required/>
                   </div>
                   <div class="mt-6 flex justify-end">
                      <x-secondary-button x-on:click="$dispatch('close')">
                         {{ __('Batal') }}
                      </x-secondary-button>
                      <x-primary-button class="ml-3">
                         {{ __('Kirim') }}
                      </x-primary-button>
                   </div>
                </form>
             </x-modal>
        {{-- End Modal Impor --}}

        </div>
    </div>
</x-app-layout>

{{-- Filter Search --}}
<script>
    const searchInput = document.getElementById('searchInput');

    // Fungsi untuk melakukan pencarian
    function search() {
        const keyword = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('tr.text-center');

        // Loop melalui setiap baris data siswa dan cek apakah sesuai dengan kata kunci
        rows.forEach(row => {
            const columns = row.querySelectorAll('td');
            const nis = columns[2].textContent.toLowerCase();
            const nama = columns[3].textContent.toLowerCase();
            const nipd = columns[5].textContent.toLowerCase();
            const kelas = columns[6].textContent.toLowerCase();

            if (nis.includes(keyword) || nama.includes(keyword) || nipd.includes(keyword) || kelas.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Tambahkan event listener untuk memanggil fungsi pencarian saat input berubah
    searchInput.addEventListener('input', search);
</script>
