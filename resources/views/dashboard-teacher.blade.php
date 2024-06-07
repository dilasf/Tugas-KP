<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

    <div class="flex-grow bg-white rounded-lg shadow-md mx-6 my-1 p-10 min-h-[calc(70vh-5px)]">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 my-4 gap-10">

                <div style="width: 259px;">
                    <x-box-blue tag="button" href="{{ route('grade.knowledge_scores.index') }}" :count="0" title="Data Nilai Pengetahuan"/>
                </div>

                <div style="width: 259px;">
                    <x-box-green tag="button" href="{{ route('grade.skill_scores.index') }}" :count="$subjectCount" title="Data Nilai Keterampilan"/>
                </div>

                <div style="width: 259px;">
                    <x-box-orange tag="button" href="{{ route('grade.attitude_scores.index') }}" :count="$classCount" title="Data Nilai Sikap"/>
                </div>

            </div>
        </div>

</x-app-layout>
