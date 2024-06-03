<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('student_data.create') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Data Siswa') }}
            </h2>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Edit Data Siswa') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm">
            <div class="p-6 text-black">
                <div class="max-h-[70vh] overflow-y-auto">
                    <form method="post" action="{{ route('student_data.store-guardian') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf

                    <!-- Form input untuk Data Wali -->
                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-zinc-600">>> Form Data Wali</h3>
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="guardian_name" value="Nama Wali" />
                            <x-text-input id="guardian_name" type="text" name="guardian_name" class="mt-1 block w-full bg-zinc-100" value="{{ old('guardian_name')}}"/>
                            <x-input-error class="mt-2 " :messages="$errors->get('guardian_name')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="guardian_nik" value="NIK Wali" />
                            <x-text-input id="guardian_nik" type="text" name="guardian_nik" class="mt-1 block w-full bg-zinc-100" value="{{ old('guardian_nik')}}" />
                            <x-input-error class="mt-2" :messages="$errors->get('guardian_nik')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="guardian_birth_year" value="Tahun Lahir Wali" />
                            <x-text-input id="guardian_birth_year" type="date" name="guardian_birth_year" class="mt-1 block w-full bg-zinc-100" value="{{ old('guardian_birth_year')}}" />
                            <x-input-error class="mt-2" :messages="$errors->get('guardian_birth_year')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="guardian_education" value="Pendidikan Wali" />
                            <x-select-input id="guardian_education" name="guardian_education" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pendidikan Wali</option>
                                <option value="SD" {{ old('guardian_education') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('guardian_education') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('guardian_education') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="Diploma" {{ old('guardian_education') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="Sarjana" {{ old('guardian_education') == 'Sarjana' ? 'selected' : '' }}>Sarjana</option>
                                <option value="Magister" {{ old('guardian_education') == 'Magister' ? 'selected' : '' }}>Magister</option>
                                <option value="Doktor" {{ old('guardian_education') == 'Doktor' ? 'selected' : '' }}>Doktor</option>
                                <option value="Lainnya" {{ old('guardian_education') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('guardian_education')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="guardian_occupation" value="Pekerjaan Wali" />
                            <x-select-input id="guardian_occupation" name="guardian_occupation" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pekerjaan Wali</option>
                                <option value="Tidak Bekerja" {{ old('guardian_occupation') == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                <option value="Ibu Rumah Tangga" {{ old('guardian_occupation') == 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                                <option value="Petani" {{ old('guardian_occupation') == 'Petani' ? 'selected' : '' }}>Petani</option>
                                <option value="Pegawai Negeri" {{ old('guardian_occupation') == 'Pegawai Negeri' ? 'selected' : '' }}>Pegawai Negeri</option>
                                <option value="Swasta" {{ old('guardian_occupation') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                                <option value="Wiraswasta" {{ old('guardian_occupation') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                <option value="Buruh" {{ old('guardian_occupation') == 'Buruh' ? 'selected' : '' }}>Buruh</option>
                                <option value="Nelayan" {{ old('guardian_occupation') == 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                <option value="Guru" {{ old('guardian_occupation') == 'Guru' ? 'selected' : '' }}>Guru</option>
                                <option value="Lainnya" {{ old('guardian_occupation') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('guardian_occupation')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="guardian_income" value="Pendapatan Wali" />
                            <x-select-input id="guardian_income" name="guardian_income" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pendapatan Wali</option>
                                <option value="Tidak Memiliki Penghasilan" {{ old('guardian_income') == 'Tidak Memiliki Penghasilan' ? 'selected' : '' }}>Tidak Memiliki Penghasilan</option>
                                <option value="< 1.000.000" {{ old('guardian_income') == '< 1.000.000' ? 'selected' : '' }}>&lt; 1.000.000</option>
                                <option value="1.000.000 - 3.000.000" {{ old('guardian_income') == '1.000.000 - 3.000.000' ? 'selected' : '' }}>1.000.000 - 3.000.000</option>
                                <option value="3.000.000 - 5.000.000" {{ old('guardian_income') == '3.000.000 - 5.000.000' ? 'selected' : '' }}>3.000.000 - 5.000.000</option>
                                <option value="5.000.000 - 10.000.000" {{ old('guardian_income') == '5.000.000 - 10.000.000' ? 'selected' : '' }}>5.000.000 - 10.000.000</option>
                                <option value="> 10.000.000" {{ old('guardian_income') == '> 10.000.000' ? 'selected' : '' }}>&gt; 10.000.000</option>
                                <option value="Lainnya" {{ old('guardian_income') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('guardian_income')" />
                        </div>

                          <!-- Form input untuk Kontak -->
                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-zinc-600">>> Form Data Kontak</h3>
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="guardian_phone_number" value="Nomor Telepon Wali" />
                            <x-text-input id="guardian_phone_number" type="text" name="guardian_phone_number" class="mt-1 block w-full bg-zinc-100" value="{{ old('guardian_phone_number')}}" />
                            <x-input-error class="mt-2" :messages="$errors->get('guardian_phone_number')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="guardian_email" value="Email Wali" />
                            <x-text-input id="guardian_email" type="email" name="guardian_email" class="mt-1 block w-full bg-zinc-100" value="{{ old('guardian_email')}}" />
                            <x-input-error class="mt-2" :messages="$errors->get('guardian_email')" />
                        </div>
                    </div>
                </div>
                        <!-- Tombol submit -->
                        <div class="flex justify-end space-x-4 mt-4 w-full">
                            <x-primary-button name="save" value="true">Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


