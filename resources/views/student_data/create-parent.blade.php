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
                    <form method="post" action="{{ route('student_data.store-parent') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf

                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-zinc-600">>> Form Data Ayah</h3>
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="father_name" value="Nama Ayah" />
                            <x-text-input id="father_name" type="text" name="father_name" class="mt-1 block w-full bg-zinc-100" value="{{ old('father_name') }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('father_name')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="father_nik" value="NIK Ayah" />
                            <x-text-input id="father_nik" type="text" name="father_nik" class="mt-1 block w-full bg-zinc-100" value="{{ old('father_nik') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('father_nik')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="father_birth_year" value="Tahun Lahir Ayah" />
                            <x-text-input id="father_birth_year" type="date" name="father_birth_year" class="mt-1 block w-full bg-zinc-100" value="{{ old('father_birth_year') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('father_birth_year')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="father_education" value="Pendidikan Ayah" />
                            <x-select-input id="father_education" name="father_education" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pendidikan Ayah</option>
                                <option value="SD" {{ old('father_education') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('father_education') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('father_education') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="Diploma" {{ old('father_education') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="Sarjana" {{ old('father_education') == 'Sarjana' ? 'selected' : '' }}>Sarjana</option>
                                <option value="Magister" {{ old('father_education') == 'Magister' ? 'selected' : '' }}>Magister</option>
                                <option value="Doktor" {{ old('father_education') == 'Doktor' ? 'selected' : '' }}>Doktor</option>
                                <option value="Lainnya" {{ old('father_education') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('father_education')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="father_occupation" value="Pekerjaan Ayah" />
                            <x-select-input id="father_occupation" name="father_occupation" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pekerjaan Ayah</option>
                                <option value="Tidak Bekerja" {{ old('father_occupation') == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                <option value="Ibu Rumah Tangga" {{ old('father_occupation') == 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                                <option value="Petani" {{ old('father_occupation') == 'Petani' ? 'selected' : '' }}>Petani</option>
                                <option value="Pegawai Negeri" {{ old('father_occupation') == 'Pegawai Negeri' ? 'selected' : '' }}>Pegawai Negeri</option>
                                <option value="Swasta" {{ old('father_occupation') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                                <option value="Wiraswasta" {{ old('father_occupation') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                <option value="Buruh" {{ old('father_occupation') == 'Buruh' ? 'selected' : '' }}>Buruh</option>
                                <option value="Nelayan" {{ old('father_occupation') == 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                <option value="Guru" {{ old('father_occupation') == 'Guru' ? 'selected' : '' }}>Guru</option>
                                <option value="Lainnya" {{ old('father_occupation') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('father_occupation')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="father_income" value="Pendapatan Ayah" />
                            <x-select-input id="father_income" name="father_income" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pendapatan Ayah</option>
                                <option value="Tidak Memiliki Penghasilan" {{ old('father_income') == 'Tidak Memiliki Penghasilan' ? 'selected' : '' }}>Tidak Memiliki Penghasilan</option>
                                <option value="< 1.000.000" {{ old('father_income') == '< 1.000.000' ? 'selected' : '' }}>&lt; 1.000.000</option>
                                <option value="1.000.000 - 3.000.000" {{ old('father_income') == '1.000.000 - 3.000.000' ? 'selected' : '' }}>1.000.000 - 3.000.000</option>
                                <option value="3.000.000 - 5.000.000" {{ old('father_income') == '3.000.000 - 5.000.000' ? 'selected' : '' }}>3.000.000 - 5.000.000</option>
                                <option value="5.000.000 - 10.000.000" {{ old('father_income') == '5.000.000 - 10.000.000' ? 'selected' : '' }}>5.000.000 - 10.000.000</option>
                                <option value="> 10.000.000" {{ old('father_income') == '> 10.000.000' ? 'selected' : '' }}>&gt; 10.000.000</option>
                                <option value="Lainnya" {{ old('father_income') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('father_income')" />
                        </div>

                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-zinc-600">>> Form Data Ibu</h3>
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="mother_name" value="Nama Ibu" />
                            <x-text-input id="mother_name" type="text" name="mother_name" class="mt-1 block w-full bg-zinc-100" value="{{ old('mother_name') }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('mother_name')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="mother_nik" value="NIK Ibu" />
                            <x-text-input id="mother_nik" type="text" name="mother_nik" class="mt-1 block w-full bg-zinc-100" value="{{ old('mother_nik') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('mother_nik')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="mother_birth_year" value="Tahun Lahir Ibu" />
                            <x-text-input id="mother_birth_year" type="date" name="mother_birth_year" class="mt-1 block w-full bg-zinc-100" value="{{ old('mother_birth_year') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('mother_birth_year')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="mother_education" value="Pendidikan Ibu" />
                            <x-select-input id="mother_education" name="mother_education" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pendidikan Ibu</option>
                                <option value="SD" {{ old('mother_education') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('mother_education') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('mother_education') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="Diploma" {{ old('mother_education') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="Sarjana" {{ old('mother_education') == 'Sarjana' ? 'selected' : '' }}>Sarjana</option>
                                <option value="Magister" {{ old('mother_education') == 'Magister' ? 'selected' : '' }}>Magister</option>
                                <option value="Doktor" {{ old('mother_education') == 'Doktor' ? 'selected' : '' }}>Doktor</option>
                                <option value="Lainnya" {{ old('mother_education') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('mother_education')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="mother_occupation" value="Pekerjaan Ibu" />
                            <x-select-input id="mother_occupation" name="mother_occupation" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pekerjaan Ibu</option>
                                <option value="Tidak Bekerja" {{ old('mother_occupation') == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                <option value="Ibu Rumah Tangga" {{ old('mother_occupation') == 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                                <option value="Petani" {{ old('mother_occupation') == 'Petani' ? 'selected' : '' }}>Petani</option>
                                <option value="Pegawai Negeri" {{ old('mother_occupation') == 'Pegawai Negeri' ? 'selected' : '' }}>Pegawai Negeri</option>
                                <option value="Swasta" {{ old('mother_occupation') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                                <option value="Wiraswasta" {{ old('mother_occupation') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                <option value="Buruh" {{ old('mother_occupation') == 'Buruh' ? 'selected' : '' }}>Buruh</option>
                                <option value="Nelayan" {{ old('mother_occupation') == 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                <option value="Guru" {{ old('mother_occupation') == 'Guru' ? 'selected' : '' }}>Guru</option>
                                <option value="Lainnya" {{ old('mother_occupation') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('mother_occupation')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="mother_income" value="Pendapatan Ibu" />
                            <x-select-input id="mother_income" name="mother_income" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pendapatan Ibu</option>
                                <option value="Tidak Memiliki Penghasilan" {{ old('mother_income') == 'Tidak Memiliki Penghasilan' ? 'selected' : '' }}>Tidak Memiliki Penghasilan</option>
                                <option value="< 1.000.000" {{ old('mother_income') == '< 1.000.000' ? 'selected' : '' }}>&lt; 1.000.000</option>
                                <option value="1.000.000 - 3.000.000" {{ old('mother_income') == '1.000.000 - 3.000.000' ? 'selected' : '' }}>1.000.000 - 3.000.000</option>
                                <option value="3.000.000 - 5.000.000" {{ old('mother_income') == '3.000.000 - 5.000.000' ? 'selected' : '' }}>3.000.000 - 5.000.000</option>
                                <option value="5.000.000 - 10.000.000" {{ old('mother_income') == '5.000.000 - 10.000.000' ? 'selected' : '' }}>5.000.000 - 10.000.000</option>
                                <option value="> 10.000.000" {{ old('mother_income') == '> 10.000.000' ? 'selected' : '' }}>&gt; 10.000.000</option>
                                <option value="Lainnya" {{ old('mother_income') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('mother_income')" />
                        </div>

                            <!-- Form input untuk Kontak -->
                            <div class="mb-4">
                                <h3 class="text-sm font-bold text-zinc-600">>> Form Data Kontak</h3>
                            </div>
                                <div class="max-w-3xl mb-6">
                                    <x-input-label for="parent_phone_number" value="Nomor Telepon Orang Tua" />
                                    <x-text-input id="parent_phone_number" type="text" name="parent_phone_number" class="mt-1 block w-full bg-zinc-100" value="{{ old('parent_phone_number')}}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('parent_phone_number')" />
                                </div>

                                <div class="max-w-3xl mb-6">
                                    <x-input-label for="parent_email" value="Email Orang Tua" />
                                    <x-text-input id="parent_email" type="email" name="parent_email" class="mt-1 block w-full bg-zinc-100" value="{{ old('parent_email')}}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('parent_email')" />
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


