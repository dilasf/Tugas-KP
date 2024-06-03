<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('student_data.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Data Siswa') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-7">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Edit Data Siswa') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm">
            <div class="p-6 text-black dark:text-gray-100">
                <div class="max-h-[70vh] overflow-y-auto">
                    <form method="post" action="{{ route('student_data.update', $student->id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Form edit untuk data siswa -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-8 max-w-3xl">
                            <x-input-label for="student_photo" value="Foto" class="text-black font-semibold"/>
                            <x-file-input id="student_photo" name="student_photo" class="mt-1 block w-full bg-zinc-100" />
                            <x-input-error class="mt-2" :messages="$errors->get('student_photo')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="nis" value="NIS" />
                            <x-text-input id="nis" type="text" name="nis" class="mt-1 block w-full bg-zinc-100" value="{{ old('nis', $student->nis) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nis')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="nisn" value="NISN" />
                            <x-text-input id="nisn" type="text" name="nisn" class="mt-1 block w-full bg-zinc-100" value="{{ old('nisn', $student->nisn) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nisn')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="nipd" value="NIPD" />
                            <x-text-input id="nipd" type="text" name="nipd" class="mt-1 block w-full bg-zinc-100" value="{{ old('nipd', $student->nipd) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nipd')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="student_name" value="Nama Siswa" />
                            <x-text-input id="student_name" type="text" name="student_name" class="mt-1 block w-full bg-zinc-100" value="{{ old('student_name', $student->student_name) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('student_name')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="gender" value="Jenis Kelamin" />
                            <div id="gender" class="mt-1 w-full flex space-x-10" required>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="gender" value="Laki-laki" {{ old('gender', $student->gender) == 'Laki-laki' ? 'checked' : '' }}>
                                    <span class="ml-2">Laki-laki</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="gender" value="Perempuan" {{ old('gender', $student->gender) == 'Perempuan' ? 'checked' : '' }}>
                                    <span class="ml-2">Perempuan</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="status" value="Status" />
                            <div id="status" class="mt-1 w-full flex space-x-16" required>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="1" {{ old('status', $student->status) == '1' ? 'checked' : '' }}>
                                    <span class="ml-2">Aktif</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="0" {{ old('status', $student->status) == '0' ? 'checked' : '' }}>
                                    <span class="ml-2">Tidak Aktif</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="class_id" value="Kelas" />
                            <x-select-input id="class_id" name="class_id" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->class_name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('class_id')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="nik" value="NIK" />
                            <x-text-input id="nik" type="text" name="nik" class="mt-1 block w-full bg-zinc-100" value="{{ old('nik', $student->nik) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nik')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="place_of_birth" value="Tempat Lahir" />
                            <x-text-input id="place_of_birth" type="text" name="place_of_birth" class="mt-1 block w-full bg-zinc-100" value="{{ old('place_of_birth', $student->place_of_birth) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('place_of_birth')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="date_of_birth" value="Tanggal Lahir" />
                            <x-text-input id="date_of_birth" type="date" name="date_of_birth" class="mt-1 block w-full bg-zinc-100" value="{{ old('date_of_birth', $student->date_of_birth) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="religion" value="Agama" />
                            <x-select-input id="religion" name="religion" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="" disabled>Pilih Agama</option>
                                <option value="Islam" {{ old('religion', $student->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('religion', $student->religion) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('religion', $student->religion) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('religion', $student->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Budha" {{ old('religion', $student->religion) == 'Budha' ? 'selected' : '' }}>Budha</option>
                                <option value="Konghucu" {{ old('religion', $student->religion) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('religion')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="address" value="Alamat" />
                            <x-text-input id="address" type="text" name="address" class="mt-1 block w-full bg-zinc-100" value="{{ old('nik', $student->address) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>
                         <!-- End Form edit untuk data siswa -->


                        <!-- Form edit untuk data tinggi dan berat badan -->
                        <div class="max-w-3xl mb-4">
                            <x-input-label for="height" value="Tinggi Badan (cm)" />
                            <x-text-input id="height" type="number" name="height" class="mt-1 block w-full bg-zinc-100" value="{{ old('height', $student->heightWeight->height) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('height')" />
                        </div>

                        <div class="max-w-3xl mb-4">
                            <x-input-label for="weight" value="Berat Badan (kg)" />
                            <x-text-input id="weight" type="number" name="weight" class="mt-1 block w-full bg-zinc-100" value="{{ old('weight', $student->heightWeight->weight) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('weight')" />
                        </div>

                        <div class="max-w-3xl mb-4">
                            <x-input-label for="head_size" value="Ukuran Kepala (cm)" />
                            <x-text-input id="head_size" type="number" name="head_size" class="mt-1 block w-full bg-zinc-100" value="{{ old('head_size', $student->heightWeight->head_size) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('head_size')" />
                        </div>
                        <!-- End Form edituntuk data tinggi dan berat badan -->

                        <!-- Lanjut Form update untuk data siswa -->
                        <div class="max-w-3xl">
                            <x-input-label for="previous_school" value="Asal Sekolah" />
                            <x-text-input id="previous_school" type="text" name="previous_school" class="mt-1 block w-full bg-zinc-100" value="{{ old('previous_school', $student->previous_school) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('previous_school')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="birth_certificate_number" value="No Akta" />
                            <x-text-input id="birth_certificate_number" type="text" name="birth_certificate_number" class="mt-1 block w-full bg-zinc-100" value="{{ old('birth_certificate_number', $student->birth_certificate_number) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('birth_certificate_number')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="child_number" value="Anak Ke" />
                            <x-text-input id="child_number" type="number" name="child_number" class="mt-1 block w-full bg-zinc-100" value="{{ old('child_number', $student->child_number) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('child_number')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="number_of_siblings" value="Jumlah Saudara" />
                            <x-text-input id="number_of_siblings" type="number" name="number_of_siblings" class="mt-1 block w-full bg-zinc-100" value="{{ old('number_of_siblings', $student->number_of_siblings) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('number_of_siblings')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="residence_type" value="Jenis Tinggal" />
                            <div id="residence_type" class="mt-2 w-full flex space-x-16" required>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="residence_type" value="Orang Tua" {{ old('residence_type', $student->residence_type) == 'Orang Tua' ? 'checked' : '' }}>
                                    <span class="ml-2">Bersama Orang Tua</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="residence_type" value="Wali" {{ old('residence_type', $student->residence_type) == 'Wali' ? 'checked' : '' }}>
                                    <span class="ml-2">Bersama Wali</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('residence_type')" />
                        </div>

                        <div class="max-w-3xl mb-6">
                            <x-input-label for="no_kk" value="NO KK" />
                            <x-text-input id="no_kk" type="text" name="no_kk" class="mt-1 block w-full bg-zinc-100" value="{{ old('no_kk', $student->no_kk) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('no_kk')" />
                        </div>

                        <!-- End Form update untuk data siswa -->

                        <!-- Form untuk data orang tua -->
                        <div class="max-w-3xl relative">
                            <div class="mb-4">
                                <div class="flex items-center justify-between bg-light-blue text-white rounded py-2 px-4 cursor-pointer" id="toggleParentForm">
                                    <span>Edit Data Orang Tua</span>
                                    <svg id="triangle" class="w-3 h-3 transition-transform duration-300 ease-in-out transform rotate-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0L10 5L0 10L0 0Z" fill="white"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Form untuk Wali-->
                        <div class="max-w-3xl relative">
                            <div class="mb-4">
                                <div class="flex items-center justify-between bg-light-blue text-white rounded py-2 px-4 cursor-pointer" id="toggleGuardianForm">
                                    <span>Edit Data Wali</span>
                                    <svg id="triangle" class="w-3 h-3 transition-transform duration-300 ease-in-out transform rotate-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0L10 5L0 10L0 0Z" fill="white"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        {{-- End --}}

                        <!-- Form input untuk data siswa -->
                        <div class="max-w-3xl">
                            <x-input-label for="transportation" value="Jenis Transportasi" />
                            <x-select-input id="transportation" name="transportation" class="mt-1 block w-full bg-zinc-100" required placeholder="Tentukan Alat Transportasi">
                                <option value="">Pilih Alat Transportasi</option>
                                <option value="Jalan Kaki" {{ old('transportation', $student->transportation) == 'Jalan Kaki' ? 'selected' : '' }}>Jalan Kaki</option>
                                <option value="Kendaraan Umum" {{ old('transportation', $student->transportation) == 'Kendaraan Umum' ? 'selected' : '' }}>Kendaraan Umum</option>
                                <option value="Kendaraan Pribadi" {{ old('transportation', $student->transportation) == 'Kendaraan Pribadi' ? 'selected' : '' }}>Kendaraan Pribadi</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('transportation')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="distance_to_school" value="Jarak Rumah Ke Sekolah (Km)" />
                            <x-text-input id="distance_to_school" type="number" name="distance_to_school" class="mt-1 block w-full bg-zinc-100" value="{{ old('distance_to_school', $student->distance_to_school) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('distance_to_school')" />
                        </div>


                            <!-- Tombol submit -->
                            <div class="flex justify-end space-x-4 mt-4 w-full">
                                <x-secondary-button tag="a" href="{{ route('student_data.index') }}">Batal</x-secondary-button>
                                <x-primary-button name="save" value="true">Perbaharui</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
      document.getElementById('toggleParentForm').addEventListener('click', function() {
        var studentId = '{{ $student->id }}';
        window.location.href = '{{ route("student_data.edit-parent", ":id") }}'.replace(':id', studentId);
    });

    document.getElementById('toggleGuardianForm').addEventListener('click', function() {
        var studentId = '{{ $student->id }}';
        window.location.href = '{{ route("student_data.edit-guardian", ":id") }}'.replace(':id', studentId);
    });
</script>

