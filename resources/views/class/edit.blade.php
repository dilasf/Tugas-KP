<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('class.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Data Kelas') }}
            </h2>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Edit Data Kelas') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm">
            <div class="p-6 text-black dark:text-gray-100">
                <div class="max-h-[70vh] overflow-y-auto">
                    <form method="post" action="{{ route('class.update', $classes->id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="max-w-3xl">
                            <x-input-label for="class_name" value="Nama Kelas" />
                            <x-text-input id="class_name" type="text" name="class_name" class="mt-1 block w-full bg-zinc-100" value="{{ old('class_name', $classes->class_name) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('class_name')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="level" value="Tingkat" />
                            <x-text-input id="level" type="text" name="level" class="mt-1 block w-full bg-zinc-100" value="{{ old('level', $classes->level) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('level')" />
                        </div>
{{--
                        <div class="max-w-3xl">
                            <x-input-label for="number_of_male_students" value="Jumlah Siswa Laki-Laki" />
                            <x-text-input id="number_of_male_students" type="text" name="number_of_male_students" class="mt-1 block w-full bg-zinc-100" value="{{ old('number_of_male_students', $classes->number_of_male_students) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('number_of_male_students')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="number_of_female_students" value="Jumlah Siswa Perempuan" />
                            <x-text-input id="number_of_female_students" type="text" name="number_of_female_students" class="mt-1 block w-full bg-zinc-100" value="{{ old('number_of_female_students', $classes->number_of_female_students) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('number_of_female_students')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="number_of_students" value="Total" />
                            <x-text-input id="number_of_students" type="text" name="number_of_students" class="mt-1 block w-full bg-zinc-100" value="{{ old('number_of_students', $classes->number_of_students) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('number_of_students')" />
                        </div> --}}

                        <div class="max-w-3xl">
                            <x-input-label for="teacher_name" value="Guru Wali"/>
                            <x-select-input id="teacher_name" name="homeroom_teacher_id" class="mt-1 block w-full bg-zinc-100" required>
                            <option value="">Pilih Guru Wali</option>
                            @foreach($teachers as $key => $value)
                                @if(old('homeroom_teacher_id', $classes->homeroom_teacher_id)== $key)
                                    <option value="{{ $key }}" selected>{{$value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value}}</option>
                                @endif
                            @endforeach
                            </x-select-input>
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="curriculum" value="Kurikulum" />
                            <x-text-input id="curriculum" type="text" name="curriculum" class="mt-1 block w-full bg-zinc-100" value="{{ old('curriculum', $classes->curriculum) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('curriculum')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="room" value="Ruangan" />
                            <x-text-input id="room" type="text" name="room" class="mt-1 block w-full bg-zinc-100" value="{{ old('room', $classes->room) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('room')" />
                        </div>

                        <div class="flex justify-end space-x-4 mt-4 w-full">
                            <x-secondary-button tag="a" href="{{ route('class.index') }}">Batal</x-secondary-button>
                            <x-primary-button name="save" value="true">Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
