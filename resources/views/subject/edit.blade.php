<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('subject.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Data Mata Pelajaran') }}
            </h2>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Edit Data Mata Pelajaran') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm">
            <div class="p-6 text-black">
                <div class="max-h-[70vh] overflow-y-auto">
                    <form method="post" action="{{ route('subject.update', $subjects->id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="max-w-3xl">
                            <x-input-label for="subject_name" value="Mata Pelajaran"/>
                            <x-text-input id="subject_name" type="text" name="subject_name" class="mt-1 block w-full bg-zinc-100" value="{{ old('subject_name', $subjects->subject_name) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('subject_name')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="kkm" value="KKM"/>
                            <x-text-input id="kkm" type="number" name="kkm" class="mt-1 block w-full bg-zinc-100" value="{{ old('kkm', $subjects->kkm) }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('kkm')" />
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
