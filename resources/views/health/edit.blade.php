<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('rapors.index', ['studentId' => $student->id]) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Data Kesehatan
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir ' . $aspectName )}}
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ $action }}">
                @csrf
                @method(isset($health) ? 'PATCH' : 'POST')

                @if ($aspectName == 'Pendengaran')
                    <div class="max-w-3xl">
                        <x-input-label for="hearing" value="Keterangan" />
                        <x-text-input id="hearing" type="text" name="hearing" class="mt-1 block w-full bg-zinc-100" value="{{ $health->hearing ?? old('hearing') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('hearing')" />
                    </div>
                @elseif ($aspectName == 'Penglihatan')
                    <div class="max-w-3xl">
                        <x-input-label for="vision" value="Keterangan" />
                        <x-text-input id="vision" type="text" name="vision" class="mt-1 block w-full bg-zinc-100" value="{{ $health->vision ?? old('vision') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('vision')" />
                    </div>
                @elseif ($aspectName == 'Gigi')
                    <div class="max-w-3xl">
                        <x-input-label for="tooth" value="Keterangan" />
                        <x-text-input id="tooth" type="text" name="tooth" class="mt-1 block w-full bg-zinc-100" value="{{ $health->tooth ?? old('tooth') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tooth')" />
                    </div>
                @endif

                <div class="flex justify-end space-x-4 mt-4 w-full">
                    <x-primary-button type="submit">
                        <span>{{ __('Simpan') }}</span>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
