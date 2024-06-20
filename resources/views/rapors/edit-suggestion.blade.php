<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('rapors.index', ['studentId' => $rapor->grade->student_id]) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Saran
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Saran')}}
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white">

                <form method="POST" action="{{ route('rapors.updateSuggestion', ['studentId' => $rapor->grade->student_id]) }}">
                    @csrf
                    @method('PATCH')

                    <div class="max-w-3xl">
                        <x-input-label for="suggestion" value="Saran" />
                        <x-text-input id="suggestion" type="text" name="suggestion" class="mt-1 block w-full bg-zinc-100" value="{{ $rapor->suggestion ?? old('suggestion') }}"/>
                        <x-input-error class="mt-2" :messages="$errors->get('suggestion')" />
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
