<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('teacher_data.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Data Guru') }}
            </h2>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Edit Data Guru') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm">
            <div class="p-6 text-black">
                <div class="max-h-[70vh] overflow-y-auto">
                    <form method="post" action="{{ route('teacher_data.update', $teachers->id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-8 max-w-3xl">
                            <x-input-label for="photo" value="Foto"/>
                            <x-file-input id="photo" name="photo" class="mt-1 block w-full"/>
                            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="nuptk" value="NUPTK"/>
                            <x-text-input id="nuptk" type="number" name="nuptk" class="mt-1 block w-full bg-zinc-100" value="{{ old('nuptk', $teachers->nuptk) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('nuptk')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="nip" value="NIP"/>
                            <x-text-input id="nip" type="number" name="nip" class="mt-1 block w-full bg-zinc-100" value="{{ old('nip', $teachers->nip) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('nuptk')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="teacher_name" value="Nama Guru"/>
                            <x-text-input id="teacher_name" type="text" name="teacher_name" class="mt-1 block w-full bg-zinc-100" value="{{ old('teacher_name', $teachers->teacher_name) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('teacher_name')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="gender" value="Jenis Kelamin" />
                            <div id="gender" class="mt-1 w-full flex space-x-10" required>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="gender" value="Laki-laki" {{ old('gender', $teachers->gender) == 'Laki-laki' ? 'checked' : '' }}>
                                    <span class="ml-2">Laki-laki</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="gender" value="Perempuan" {{ old('gender', $teachers->gender) == 'Perempuan' ? 'checked' : '' }}>
                                    <span class="ml-2">Perempuan</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                        </div>

                      <div class="max-w-3xl">
                            <x-input-label for="status" value="Status" />
                            <div id="status" class="mt-1 w-full flex space-x-16" required>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="1" {{ old('status', $teachers->status) == '1' ? 'checked' : '' }}>
                                    <span class="ml-2">Aktif</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="0" {{ old('status', $teachers->status) == '0' ? 'checked' : '' }}>
                                    <span class="ml-2">Tidak Aktif</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="placeOfbirth" value="Tempat Lahir"/>
                            <x-text-input id="placeOfbirth" type="text" name="placeOfbirth" class="mt-1 block w-full bg-zinc-100" value="{{ old('placeOfbirth', $teachers->placeOfbirth) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('placeOfbirth')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="dateOfbirth" value="Tanggal Lahir"/>
                            <x-text-input id="dateOfbirth" type="date" name="dateOfbirth" class="mt-1 block w-full bg-zinc-100" value="{{ old('dateOfbirth', $teachers->dateOfbirth) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('dateOfbirth')" />
                        </div>

                       <div class="max-w-3xl">
                            <x-input-label for="religion" value="Agama" />
                            <x-select-input id="religion" name="religion" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="" disabled>Pilih Agama</option>
                                <option value="Islam" {{ old('religion', $teachers->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('religion', $teachers->religion) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('religion', $teachers->religion) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('religion', $teachers->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Budha" {{ old('religion', $teachers->religion) == 'Budha' ? 'selected' : '' }}>Budha</option>
                                <option value="Konghucu" {{ old('religion', $teachers->religion) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('religion')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="address" value="Alamat"/>
                            <x-text-input id="address" type="text" name="address" class="mt-1 block w-full bg-zinc-100" value="{{ old('address', $teachers->address) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="mobile_phone" value="Nomor Telepon"/>
                            <x-text-input id="mobile_phone" type="text" name="mobile_phone" class="mt-1 block w-full bg-zinc-100" value="{{ old('mobile_phone', $teachers->mobile_phone) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('mobile_phone')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="employment_status" value="Status Kepegawaian"/>
                            <x-text-input id="employment_status" type="text" name="employment_status" class="mt-1 block w-full bg-zinc-100" value="{{ old('employment_status', $teachers->employment_status) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('employment_status')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="typesOfCAR" value="Jenis PTK"/>
                            <x-text-input id="typesOfCAR" type="text" name="typesOfCAR" class="mt-1 block w-full bg-zinc-100" value="{{ old('typesOfCAR', $teachers->typesOfCAR) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('typesOfCAR')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="prefix" value="Gelar Depan"/>
                            <x-text-input id="prefix" type="text" name="prefix" class="mt-1 block w-full bg-zinc-100" value="{{ old('prefix', $teachers->prefix) }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('prefix')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="suffix" value="Gelar Belakang"/>
                            <x-text-input id="suffix" type="text" name="suffix" class="mt-1 block w-full bg-zinc-100" value="{{ old('suffix', $teachers->suffix) }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('suffix')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="education_Level" value="Pendidikan Wali" />
                            <x-select-input id="education_Level" name="education_Level" class="mt-1 block w-full bg-zinc-100" required>
                                <option value="">Pilih Pendidikan</option>
                                <option value="SD" {{ old('education_Level', $teachers->education_Level) == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('education_Level', $teachers->education_Level) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('education_Level', $teachers->education_Level) == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="Diploma" {{ old('education_Level', $teachers->education_Level) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="S1" {{ old('education_Level', $teachers->education_Level) == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('education_Level', $teachers->education_Level) == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('education_Level', $teachers->education_Level) == 'S3' ? 'selected' : '' }}>S3</option>
                                <option value="Magister" {{ old('education_Level', $teachers->education_Level) == 'Magister' ? 'selected' : '' }}>Magister</option>
                                <option value="Doktor" {{ old('education_Level', $teachers->education_Level) == 'Doktor' ? 'selected' : '' }}>Doktor</option>
                                <option value="Lainnya" {{ old('education_Level', $teachers->education_Level) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('education_Level')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="fieldOfStudy" value="Jurusan/Prodi"/>
                            <x-text-input id="fieldOfStudy" type="text" name="fieldOfStudy" class="mt-1 block w-full bg-zinc-100" value="{{ old('fieldOfStudy', $teachers->fieldOfStudy) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('fieldOfStudy')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="certification" value="Sertifikasi"/>
                            <x-text-input id="certification" type="text" name="certification" class="mt-1 block w-full bg-zinc-100" value="{{ old('certification', $teachers->certification) }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('certification')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="startDateofEmployment" value="Tanggal Mulai Tugas"/>
                            <x-text-input id="startDateofEmployment" type="date" name="startDateofEmployment" class="mt-1 block w-full bg-zinc-100" value="{{ old('startDateofEmployment', $teachers->startDateofEmployment) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('startDateofEmployment')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="additional_Duties" value="Tugas Tambahan"/>
                            <x-text-input id="additional_Duties" type="text" name="additional_Duties" class="mt-1 block w-full bg-zinc-100" value="{{ old('additional_Duties', $teachers->additional_Duties) }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('additional_Duties')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="teaching" value="Mengajar"/>
                            <x-text-input id="teaching" type="text" name="teaching" class="mt-1 block w-full bg-zinc-100" value="{{ old('teaching', $teachers->teaching) }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('teaching')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="competency" value="Kompetensi"/>
                            <x-text-input id="competency" type="text" name="competency" class="mt-1 block w-full bg-zinc-100" value="{{ old('competency', $teachers->competency) }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('competency')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="mail" value="E-mail"/>
                            <x-text-input id="mail" type="text" name="mail" class="mt-1 block w-full bg-zinc-100" value="{{ old('mail', $teachers->mail) }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('mail')" />
                        </div>
                        <div class="flex justify-end space-x-4 mt-4 w-full">
                            <x-secondary-button tag="a" href="{{ route('teacher_data.index') }}">Batal</x-secondary-button>
                            <x-primary-button>Perbaharui</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
