<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('rapors.index', ['studentId' => $student->id]) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Data Tinggi dan Berat Badan
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('height_weights.update', ['id' => $rapor->id, 'semester_year_id' => $selectedSemesterYearId]) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="height" class="block font-medium text-sm text-gray-700">Height (cm)</label>
                            <input type="text" id="height" name="height" value="{{ $heightWeight->height ?? '' }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="weight" class="block font-medium text-sm text-gray-700">Weight (kg)</label>
                            <input type="text" id="weight" name="weight" value="{{ $heightWeight->weight ?? '' }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="head_size" class="block font-medium text-sm text-gray-700">Head Size (cm)</label>
                            <input type="text" id="head_size" name="head_size" value="{{ $heightWeight->head_size ?? '' }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Height and Weight
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
