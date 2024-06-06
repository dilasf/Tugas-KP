<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('grade.knowledge_scores.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Data Assessment') }}
            </h2>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Tambah Data Assessment') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm">
            <div class="p-6 text-black">
                <div class="max-h-[70vh] overflow-y-auto">
                    <form method="post" action="{{ route('grade.knowledge_scores.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <div class="max-w-3xl">
                            <x-input-label for="assessment_type" value="Assessment Type"/>
                            <x-text-input id="assessment_type" type="text" name="assessment_type" class="mt-1 block w-full bg-zinc-100" value="{{ old('assessment_type') }}" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('assessment_type')" />
                        </div>

                        <div class="flex justify-end space-x-4 mt-4 w-full">
                            <x-secondary-button tag="a" href="{{ route('grade.knowledge_scores.index') }}">Batal</x-secondary-button>
                            <x-primary-button>Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
