<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Data Guru') }}
        </h2>
    </x-slot>
            <div class="bg-white rounded-lg shadow-md mx-4 overflowhidden">

                <div class="flex justify-between items-center px-6 py-4">
                    <div style="order: 1;">
                        <x-search-box>
                            <input id="searchInput" class="py-3 px-4 w-full border-gray-200 rounded-lg text-sm
                            focus:border-light-blue focus:ring-light-blue disabled:opacity-50 disabled:pointer-events-none"
                            type="text" placeholder="Cari Guru" value="">
                        </x-search-box>
                    </div>
                    <div style="order: 2;">
                        <div class="flex items-center">
                            <x-edit-primary-button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'import-teacher')"   class="mr-4 font-semibold
                                inline-flex items-center"
                                style="padding: 0.5rem 1rem;">
                                <img src="{{ asset('img/import_logo.png') }}" class="w-[18px] h-[18px]"  alt="Import Icon">
                                <span class="text-12px ml-1">{{ __('Import Excel') }}</span>
                                </x-edit-primary-button>
                            <x-primary-button tag="a" href="{{route('teacher_data.create')}}"
                            style="padding: 0.5rem 1rem;">
                                <span class="text-12px ml-1">{{ __('+ Tambah Data Guru') }}</span>
                            </x-primary-button>
                        </div>
                    </div>
                </div>

                <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
                    <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto">
                        <x-slot name="header">
                            <tr>
                                <th>No</th>
                                <th>Photo</th>
                                <th>NIP</th>
                                <th>Nuptk</th>
                                <th>Nama</th>
                                <th>JK</th>
                                <th>Jenis PTK</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </x-slot>
                        @php $num=1; @endphp
                        @foreach($teachers as $teach)
                        <tr>
                            <td>{{ $num++ }} </td>
                            <td>

                                    @if ($teach->photo)
                                        <img src="{{ asset('storage/photos/'.$teach->photo) }}" alt="{{ $teach->teacher_name }}" class="w-16 h-auto mb-1">
                                    @else
                                        <img src="{{ asset('img/profil.png') }}" alt="No photo" class="w-[50px] h-auto mb-1">
                                    @endif
                            </td>
                            <td>{{ $teach->nip }}</td>
                            <td>{{ $teach->nuptk }}</td>
                            <td>{{ $teach->teacher_name }}</td>
                            <td>{{ $teach->gender === 'Perempuan' ? 'P' : 'L' }}</td>
                            <td>{{ $teach->typesOfCAR }}</td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    @if($teach->status == 1)
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
                                <!-- Detail Button -->
                                <x-detail-primary-button
                                    data-teacher-details="{{ json_encode($teach) }}"
                                    data-teacher-id="{{ $teach->id }}"
                                    x-on:click.prevent="
                                        $dispatch('open-modal', 'teacher-details');
                                        $dispatch('set-teacher-id', {{ $teach->id }});"
                                    class="flex items-center justify-center min-w-[90px]">
                                    <img src="{{ asset('img/detail_logo.png') }}" class="w-[13px] h-[13px]">
                                    <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Detail') }}</span>
                                </x-detail-primary-button>

                                <!-- Edit Button -->
                                <x-edit-primary-button
                                    tag="a" href="{{ route('teacher_data.edit', ['id' => $teach->id]) }}"
                                    class="flex items-center justify-center min-w-[50px]">
                                    <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                                    <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                                </x-edit-primary-button>

                                <x-danger-button
                                    x-data=""
                                    x-on:click.prevent="
                                        $dispatch('open-modal', 'confirm-book-deletion');
                                        $dispatch('set-action', '{{ route('teacher_data.destroy', $teach->id) }}');"
                                    class="flex items-center justify-center min-w-[90px]">
                                    <img src="{{ asset('img/garbage_logo.png') }}" class="w-[13px] h-[13px]">
                                    <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Hapus') }}</span> <!-- Menyertakan teks Hapus -->
                                </x-danger-button>

                            </td>

                        </tr>
                        @endforeach
                    </x-table>

                    <!-- Detail Informasi Modal -->
                    <x-detail-modal name="teacher-details" focusable maxWidth="xl">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Detail Informasi Guru') }}
                            </h2>
                            <div id="teacher-details-modal-content" style="max-height: 60vh; overflow-y: auto;">
                                <div class="flex flex-col items-center mt-4">
                                    <div class="w-24 h-32 mb-4 flex items-center justify-center">
                                        <img id="teacher-photo" src="{{ asset('img/profil.png') }}" alt="No photo" class="object-cover" style="width: auto; height: auto; max-width: 100%; max-height: 100%;">
                                    </div>
                                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100 text-center mt-2" id="teacher-name"></p>
                                    <div class="w-full grid grid-cols-2 gap-y-3 mt-4 text-sm text-gray-600">

                                        <p>{{ __('Status') }}</p>
                                        <p id="teacher-status" class="ml-2"></p>

                                        <p>{{ __('E-mail') }}</p>
                                        <p id="teacher-mail" class="ml-2"></p>

                                        <p>{{ __('NUPTK') }}</p>
                                        <p id="teacher-nuptk" class="ml-2"></p>

                                        <p>{{ __('NIP') }}</p>
                                        <p id="teacher-nip" class="ml-2"></p>

                                        <p>{{ __('Jenis Kelamin') }}</p>
                                        <p id="teacher-gender" class="ml-2"></p>

                                        <p>{{ __('Tempat Lahir') }}</p>
                                        <p id="teacher-placeOfbirth" class="ml-2"></p>

                                        <p>{{ __('Tanggal Lahir') }}</p>
                                        <p id="teacher-dateOfbirth" class="ml-2"></p>

                                        <p>{{ __('Agama') }}</p>
                                        <p id="teacher-religion" class="ml-2"></p>

                                        <p>{{ __('Alamat') }}</p>
                                        <p id="teacher-address" class="ml-2"></p>

                                        <p>{{ __('No HP') }}</p>
                                        <p id="teacher-mobile_phone" class="ml-2"></p>

                                        <p>{{ __('Status Kepegawaian') }}</p>
                                        <p id="teacher-employment_status" class="ml-2"></p>

                                        <p>{{ __('Jenis PTK') }}</p>
                                        <p id="teacher-typesOfCAR" class="ml-2"></p>

                                        <p>{{ __('Gelar Depan') }}</p>
                                        <p id="teacher-prefix" class="ml-2"></p>

                                        <p>{{ __('Gelar Belakang') }}</p>
                                        <p id="teacher-suffix" class="ml-2"></p>

                                        <p>{{ __('Jenjang') }}</p>
                                        <p id="teacher-education_Level" class="ml-2"></p>

                                        <p>{{ __('Jurusan/Prodi') }}</p>
                                        <p id="teacher-fieldOfStudy" class="ml-2"></p>

                                        <p>{{ __('Sertifikasi') }}</p>
                                        <p id="teacher-certification" class="ml-2"></p>

                                        <p>{{ __('TMT Kerja') }}</p>
                                        <p id="teacher-startDateofEmployment" class="ml-2"></p>

                                        <p>{{ __('Tugas Tambahan') }}</p>
                                        <p id="teacher-additional_Duties" class="ml-2"></p>

                                        <p>{{ __('Mengajar') }}</p>
                                        <p id="teacher-teaching" class="ml-2"></p>

                                        <p>{{ __('Kompetensi') }}</p>
                                        <p id="teacher-competency" class="ml-2"></p>

                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-between">
                                <x-secondary-button x-on:click="$dispatch('close')" style="text-transform: none;">
                                    {{ __('Batal') }}
                                </x-secondary-button>
                                <a id="edit-button" class="flex items-center justify-center min-w-[60px]">
                                    <x-edit-primary-button>
                                        {{ __('Edit') }}
                                    </x-edit-primary-button>
                                </a>

                            </div>
                        </div>
                    </x-detail-modal>



                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            document.querySelectorAll('[data-teacher-details]').forEach(item => {
                                item.addEventListener('click', function () {
                                    const teacherDetails = JSON.parse(this.getAttribute('data-teacher-details'));
                                     const teacherId = this.getAttribute('data-teacher-id');
                                    document.getElementById('teacher-photo').src = teacherDetails.photo ? `{{ asset('storage/photos') }}/${teacherDetails.photo}` : `{{ asset('img/profil.png') }}`;
                                    document.getElementById('teacher-name').innerText = teacherDetails.teacher_name;
                                    document.getElementById('teacher-status').innerHTML = ` :
                                    ${
                                        teacherDetails.status === 1 ?
                                        `<span class="py-1 px-2 inline-flex items-center text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                            Aktif
                                        </span>` :
                                        `<span class="py-1 px-2 inline-flex items-center text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                            <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                            </svg>
                                            Tidak Aktif
                                        </span>`
                                    }
                                `;
                                    document.getElementById('teacher-mail').innerText = teacherDetails.mail ?  `: ${teacherDetails.mail}` : ': -';
                                    document.getElementById('teacher-nuptk').innerText = teacherDetails.nuptk ?  `: ${teacherDetails.nuptk}` : ': -';
                                    document.getElementById('teacher-placeOfbirth').innerText = teacherDetails.placeOfbirth ? `: ${teacherDetails.placeOfbirth}` : ': -';
                                    document.getElementById('teacher-dateOfbirth').innerText = teacherDetails.dateOfbirth ? `: ${teacherDetails.dateOfbirth}` : ': -';
                                    document.getElementById('teacher-gender').innerText = teacherDetails.gender ? `: ${teacherDetails.gender}` : ': -';
                                    document.getElementById('teacher-religion').innerText = teacherDetails.religion ? `: ${teacherDetails.religion}` : ': -';
                                    document.getElementById('teacher-address').innerText = teacherDetails.address ? `: ${teacherDetails.address}` : ': -';
                                    document.getElementById('teacher-mobile_phone').innerText = teacherDetails.mobile_phone ? `: ${teacherDetails.mobile_phone}` : ': -';
                                    document.getElementById('teacher-nip').innerText = teacherDetails.nip ? `: ${teacherDetails.nip}` : ': -';
                                    document.getElementById('teacher-employment_status').innerText = teacherDetails.employment_status ? `: ${teacherDetails.employment_status}` : ': -';
                                    document.getElementById('teacher-typesOfCAR').innerText = teacherDetails.typesOfCAR ? `: ${teacherDetails.typesOfCAR}` : ': -';
                                    document.getElementById('teacher-prefix').innerText = teacherDetails.prefix ? `: ${teacherDetails.prefix}` : ': -';
                                    document.getElementById('teacher-suffix').innerText = teacherDetails.suffix ? `: ${teacherDetails.suffix}` : ': -';
                                    document.getElementById('teacher-education_Level').innerText = teacherDetails.education_Level ? `: ${teacherDetails.education_Level}` : ': -';
                                    document.getElementById('teacher-fieldOfStudy').innerText = teacherDetails.fieldOfStudy ? `: ${teacherDetails.fieldOfStudy}` : ': -';
                                    document.getElementById('teacher-certification').innerText = teacherDetails.certification ? `: ${teacherDetails.certification}` : ': -';
                                    document.getElementById('teacher-startDateofEmployment').innerText = teacherDetails.additional_Duties ? `: ${teacherDetails.startDateofEmployment}` : ': -';
                                    document.getElementById('teacher-additional_Duties').innerText = teacherDetails.nuptk ? `: ${teacherDetails.additional_Duties}` : ': -';
                                    document.getElementById('teacher-teaching').innerText = teacherDetails.teaching ? `: ${teacherDetails.teaching}` : ': -';
                                    document.getElementById('teacher-competency').innerText = teacherDetails.competency ? `: ${teacherDetails.competency}` : ': -';
                                    const editButton = document.querySelector('#edit-button');
                                    editButton.href = "{{ route('teacher_data.edit', ['id' => ':id']) }}".replace(':id', teacherId);
                                });
                            });
                        });
                    </script>


                    <x-modal name="import-teacher" focusable maxWidth="xl">
                        <form method="post" action="{{ route('teacher_data.import') }}"
                           class="p-6" enctype="multipart/form-data">
                           @csrf
                           <h2 class="text-lg font-medium text-gray-900">
                              {{ __('Import Data Guru') }}
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

                    <x-modal name="confirm-book-deletion" focusable maxWidth="xl">
                        <form method="post" x-bind:action="action" class="p-6">
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


                </div>
            </div>
</x-app-layout>

<script>
    const searchInput = document.getElementById('searchInput');

    // Fungsi untuk melakukan pencarian
    function search() {
        const keyword = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        // Loop melalui setiap baris data guru dan cek apakah sesuai dengan kata kunci
        rows.forEach(row => {
            const columns = row.querySelectorAll('td');
            const nama = columns[3].textContent.toLowerCase();
            const nip = columns[4].textContent.toLowerCase();
            const nuptk = columns[2].textContent.toLowerCase()

            if (nuptk.includes(keyword) || nama.includes(keyword) || nip.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Tambahkan event listener untuk memanggil fungsi pencarian saat input berubah
    searchInput.addEventListener('input', search);

</script>
