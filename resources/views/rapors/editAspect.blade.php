<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('rapors.index', ['studentId' => $student]) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Kompetensi - {{ $aspectName }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir '. $aspectName)}}
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white">
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @method('PATCH')

                    <div class="max-w-3xl">
                        @if ($aspectName == 'Sikap Sosial')
                            <x-input-label for="social_attitudes" value="Keterangan" />
                            <x-text-input id="social_attitudes" type="text" name="social_attitudes" class="mt-1 block w-full bg-zinc-100" value="{{ $rapor->social_attitudes ?? old('social_attitudes') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('social_attitudes')" />
                        @elseif ($aspectName == 'Sikap Spiritual')
                            <x-input-label for="spiritual_attitude" value="Keterangan" />
                            <x-text-input id="spiritual_attitude" type="text" name="spiritual_attitude" class="mt-1 block w-full bg-zinc-100" value="{{ $rapor->spiritual_attitude ?? old('spiritual_attitude') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('spiritual_attitude')" />
                        @endif
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
