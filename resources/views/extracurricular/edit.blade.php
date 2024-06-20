<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('rapors.index', ['studentId' => $student]) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Data Ekstra Kulikuler
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Edit Ekstra Kulikuler') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white">
                <form method="POST" action="{{ route('extracurriculars.update', ['studentId' => $student->id, 'extracurricularId' => $extracurricular->id]) }}">
                    @csrf
                    @method('PATCH')

                    <div class="max-w-3xl">
                        <x-input-label for="activity" value="Kegiatan Ekstrakurikuler" />
                        <x-text-input id="activity" type="text" name="activity" class="mt-1 block w-full bg-zinc-100" value="{{ $extracurricular->activity ?? old('activity') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('activity')" />
                    </div>

                    <div class="max-w-3xl py-8">
                        <x-input-label for="description" value="Keterangan" />
                        <x-text-input id="description" type="text" name="description" class="mt-1 block w-full bg-zinc-100" value="{{ $attitudeScore->description ?? old('description') }}"/>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="flex justify-end space-x-4 mt-4 w-full">
                        <x-primary-button type="submit">
                            <span>{{ __('Simpan') }}</span>
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

