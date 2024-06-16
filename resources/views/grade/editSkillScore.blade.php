<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('grade.detailSkillScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id]) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Penilaian {{ $classSubject->class->class_name }}
                - Semester {{ $semesters->firstWhere('id', $selectedSemesterId)->semester }}
                Tahun {{ $semesters->firstWhere('id', $selectedSemesterId)->year }}
            </h2>

        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Nilai '.  $classSubject->subject->subject_name) }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white">

                <form method="POST" action="{{ route('grade.updateSkillScore', ['studentId' => $student->id, 'classSubjectId' => $classSubject->id, 'assessmentType' => $assessmentType]) }}">
                    @csrf
                    @method('PATCH')

                    <div class="max-w-3xl">
                        <x-input-label for="score" value="Nilai" />
                        <x-text-input id="score" type="text" name="score" class="mt-1 block w-full bg-zinc-100" value="{{ $skillScore->score ?? old('score') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('score')" />
                    </div>

                    <div class="max-w-3xl py-8">
                        <x-input-label for="description" value="Deskripsi" />
                        <x-text-input id="description" type="text" name="description" class="mt-1 block w-full bg-zinc-100" value="{{ $skillScore->description ?? old('description') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="flex justify-end space-x-4 mt-4 w-full">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

