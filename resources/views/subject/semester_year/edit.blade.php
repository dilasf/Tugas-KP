<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('subject.semester_year.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Data Semester dan Tahun Ajaran') }}
            </h2>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Edit Data Semester dan Tahun Ajaran') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm">
            <div class="p-6 text-black">
                <div class="max-h-[70vh] overflow-y-auto">
                    <form method="post" action="{{ route('subject.semester_year.update', $semester_years->id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')
                        <div class="max-w-3xl">
                            <x-input-label for="semester" value="Semester" />
                            <x-text-input id="semester" type="text" name="semester" class="mt-1 block w-full bg-zinc-100" value="{{ old('semester', $semester_years->semester)}}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('semester')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="year" value="Tahun Terbit" />
                            <x-text-input id="year" type="number" name="year" class="mt-1 block w-full bg-zinc-100" value="{{ old('year', $semester_years->year)}}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('year')" />
                        </div>

                        <div class="flex justify-end space-x-4 mt-4 w-full">
                            <x-secondary-button tag="a" href="{{ route('subject.semester_year.create') }}">Batal</x-secondary-button>
                            <x-primary-button name="save" value="true">Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
