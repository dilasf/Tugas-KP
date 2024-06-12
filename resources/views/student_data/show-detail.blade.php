<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('student_data.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Data Siswa') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-md mx-20 overflow-hidden">
        <div class="max-h-[calc(100vh-150px)] overflow-y-auto p-4">
            <div class="text-center mb-2 font-bold text-xl">DETAIL INFORMASI SISWA</div>

            <div class="w-24 h-32 mb-6 flex flex-col items-center justify-center mx-auto">
                <img id="student-photo" class="object-contain mb-5 mt-20" src="{{ $student->student_photo ? asset('storage/photos/' . $student->student_photo) : asset('img/profil.png') }}" alt="Student Photo">
                <div class="text-center">
                    <p class="font-semibold uppercase text-gray-900">{{ strtoupper ($student->student_name) }}</p>
                    <div class="flex justify-center items-center">
                        <p class="font-medium text-16px text-gray-600 inline-block w-[60px]">Status</p>
                        <p class="text-gray-500 text-16px inline-block w-[30px]">:</p>
                        @if($student->status == 1)
                            <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full dark:bg-green-500/10 dark:text-green-500">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                                Aktif
                            </span>
                        @else
                            <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                </svg>
                                Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            @if(isset($student))
            <div class='text-lg font-medium text-gray-900 mt-10'>

                <!-- Data Siswa dan Data Wali -->
                <div class="grid grid-cols-2 gap-8">
                    <!-- Data Siswa -->
                    <div class="col-span-1 mx-5 py-20">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 rounded-full bg-gray-800 mr-3"></div>
                                <h3 class="font-semibold text-md text-gray-800">Detail Data Siswa</h3>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">NIS</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->nis ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">NISN</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->nisn ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">NIPD</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->nipd ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Kelas</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->class_id ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Jenis Kelamin</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->gender ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Tinggi Badan</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->heightWeight->height.' Cm' ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Berat Badan</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->heightWeight->weight.' Kg' ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Besar Kepala</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->heightWeight->head_size.' Cm' ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">NIK</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->nik ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Tempat Lahir</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->place_of_birth ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Tanggal Lahir</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->date_of_birth ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Agama</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->religion ?? 'N/A'}}</p>
                            </div>

                            <!-- Data Alamat -->
                            <div class="mb-2 flex">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[152px]">Alamat</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <div class="text-gray-500 text-16px inline-block">
                                    @php
                                    $address = $student->address ?? 'N/A';
                                    $address_lines = explode("\n", wordwrap($address, 35, "\n", false));
                                    @endphp
                                    @foreach($address_lines as $line)
                                    <p>{{ $line }}</p>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Kebutuhan Khusus</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                @if(isset($student->special_needs) && $student->special_needs == 1)
                                    <p class="text-green-500 text-16px inline-block">Ya</p>
                                @else
                                    <p class="text-red-500 text-16px inline-block">Tidak</p>
                                @endif
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Sekolah Asal</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->previous_school ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">No Akta</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->birth_certificate_number ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Jenis Tinggal</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->residence_type ?? 'N/A'}}</p>
                            </div>

                            <div class=" mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]"">N0 KK</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->no_kk ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Anak Ke-</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->child_number ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block">Jumlah Bersaudara</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->number_of_siblings ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Transportasi</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->transportation ?? 'N/A'}}</p>
                            </div>

                            <div class="mb-2">
                                <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Jarak Ke Sekolah</p>
                                <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                                <p class="text-gray-500 text-16px inline-block">{{ $student->distance_to_school.' Km' ?? 'N/A'}}</p>
                            </div>
                    </div>

                    <!-- Data Wali -->
                    <div class="col-span-1 mx-10 py-20">
                        <div class="flex items-center mb-2">
                            <div class="w-2 h-2 rounded-full bg-gray-800 mr-3"></div>
                            <h3 class="font-semibold text-md text-gray-800">Detail Data Orang Tua/Wali</h3>
                        </div>

                         <!-- Data Orang Tua (Ayah dan Ibu) -->
                    @if($student->guardian->father_name || $student->guardian->mother_name)
                    <div>
                        <!-- Data Ayah -->
                        @if($student->guardian->father_name)
                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Nama Ayah</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->father_name ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">NIK Ayah</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->father_nik ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Tahun Lahir Ayah</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->father_birth_year ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Pendidikan Ayah</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->father_education ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Pekerjaan Ayah</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->father_occupation ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Penghasilan Ayah</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->father_income ?? 'N/A'}}</p>
                        </div>

                        @endif

                        <!-- Data Ibu -->
                        @if($student->guardian->mother_name)
                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Nama Ibu</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->mother_name ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">NIK Ibu</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->mother_nik ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Tahun Lahir Ibu</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->mother_birth_year ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Pendiikan Ibu</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->mother_education ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Pekerjaan Ibu</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->mother_occupation ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Penghasilan Ibu</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->mother_income ?? 'N/A'}}</p>
                        </div>
                    </div>
                    @endif

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">No Hp Orang Tua</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->parent_phone_number ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Email Orang Tua</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->parent_email ?? 'N/A'}}</p>
                        </div>
                    </div>
                    @endif

                        <!-- Data Wali -->
                    @if($student->guardian->guardian_name)
                    <div>
                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Nama Wali</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->guardian_name ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">NIK Wali</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->guardian_nik ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Tahun Lahir Wali</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->guardian_birth_year ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Pendidikan Wali</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->guardian_education ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Pekerjaan Wali</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->guardian_occupation ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Penghasilan Wali</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->guardian_income ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">No HP Wali</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->guardian_phone_number ?? 'N/A'}}</p>
                        </div>

                        <div class="flex items-center mb-2">
                            <p class="font-medium text-16px text-gray-600 mr-2 inline-block w-[147px]">Email Wali</p>
                            <p class="text-gray-500 text-16px inline-block w-[10px]">:</p>
                            <p class="text-gray-500 text-16px inline-block">{{ $student->guardian->guardian_email ?? 'N/A'}}</p>
                        </div>
                    </div>
                    @endif

                    </div>
                </div>
            </div>
            @else
            <p class="text-gray-700">Detail siswa tidak ditemukan.</p>
            @endif
        </div>
    </div>
</x-app-layout>
