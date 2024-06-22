<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('rapors.index', ['studentId' => $student->id]) }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-3 cursor-pointer">
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Nilai untuk Mata Pelajaran {{ $classSubject->subject->subject_name }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Nilai Siswa') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm">
            <div class="p-6 text-black">
                <form method="POST" action="{{ route('rapors.update', ['rapor' => $rapor->id]) }}">
                    @csrf
                    @method('PATCH')

                    <div class="font-semibold text-md py-3">Nilai Pengetahuan</div>
                    <div class="max-w-3xl">
                        <x-input-label for="average_knowledge_score" value="Nilai" />
                        <x-text-input id="average_knowledge_score" type="text" name="average_knowledge_score" class="mt-1 block w-full bg-zinc-100" value="{{ $rapor->grade->average_knowledge_score ?? old('average_knowledge_score') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('average_knowledge_score')" />
                    </div>

                    <div class="max-w-3xl py-8">
                        <x-input-label for="descriptionKnowledge" value="Deskripsi" />
                        <x-text-input id="descriptionKnowledge" type="text" name="descriptionKnowledge" class="mt-1 block w-full bg-zinc-100" value="{{ $rapor->grade->descriptionKnowledge ?? old('descriptionKnowledge') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('descriptionKnowledge')" />
                    </div>

                    <div class="font-semibold text-md py-3">Nilai Keterampilan</div>
                    <div class="max-w-3xl">
                        <x-input-label for="average_skill_score" value="Nilai" />
                        <x-text-input id="average_skill_score" type="text" name="average_skill_score" class="mt-1 block w-full bg-zinc-100" value="{{ $rapor->grade->average_skill_score ?? old('average_skill_score') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('average_skill_score')" />
                    </div>

                    <div class="max-w-3xl py-8">
                        <x-input-label for="descriptionSkill" value="Deskripsi" />
                        <x-text-input id="descriptionSkill" type="text" name="descriptionSkill" class="mt-1 block w-full bg-zinc-100" value="{{ $rapor->grade->descriptionSkill ?? old('descriptionSkill') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('descriptionSkill')" />
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
