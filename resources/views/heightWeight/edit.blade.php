<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('rapors.index', ['studentId' => $student->id, 'semester_year_id' => $semester_year_id]) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isset($heightWeight) ? 'Edit' : 'Tambah' }} Data {{ $aspectName }} - Semester {{ $semester_year_id }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-10">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('height_weights.update', ['studentId' => $student->id, 'heightWeightId' => $heightWeight ? $heightWeight->id : null, 'aspectName' => $aspectName]) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="semester_year_id" value="{{ $semester_year_id }}">

                @if ($aspectName == 'Tinggi Badan')
                    <div class="max-w-3xl">
                        <x-input-label for="height" value="Tinggi Badan (cm)" />
                        <x-text-input id="height" type="number" name="height" class="mt-1 block w-full bg-zinc-100" value="{{ $heightWeight ? $heightWeight->height : old('height') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('height')" />
                    </div>
                @elseif ($aspectName == 'Berat Badan')
                    <div class="max-w-3xl">
                        <x-input-label for="weight" value="Berat Badan (kg)" />
                        <x-text-input id="weight" type="number" name="weight" class="mt-1 block w-full bg-zinc-100" value="{{ $heightWeight ? $heightWeight->weight : old('weight') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('weight')" />
                    </div>
                @elseif ($aspectName == 'Ukuran Kepala')
                    <div class="max-w-3xl">
                        <x-input-label for="head_size" value="Ukuran Kepala (cm)" />
                        <x-text-input id="head_size" type="number" name="head_size" class="mt-1 block w-full bg-zinc-100" value="{{ $heightWeight ? $heightWeight->head_size : old('head_size') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('head_size')" />
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
