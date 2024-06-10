<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Nilai Sikap
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('grade.updateAttitudeScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'assessmentType' => $assessmentType, 'semesterYearId' => $semesterYear->id]) }}">
                        @csrf
                        @method('patch')

                        <!-- Tambahkan input tersembunyi untuk menyertakan semesterYearId -->
                        <input type="hidden" name="semesterYearId" value="{{ $semesterYear->id }}">

                        <div class="max-w-3xl">
                            <x-input-label for="score" value="Nilai" />
                            <x-text-input id="score" type="text" name="score" class="mt-1 block w-full bg-zinc-100" value="{{ $attitudeScore->score ?? old('score') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('score')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="description" value="Deskripsi" />
                            <x-text-input id="description" type="text" name="description" class="mt-1 block w-full bg-zinc-100" value="{{ $attitudeScore->description ?? old('description') }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline">
                                Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
