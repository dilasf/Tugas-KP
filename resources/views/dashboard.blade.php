<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

        <div class="bg-white p-5 mx-5 rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-5">
                <div style="width: 259px;">
                        <x-box-blue tag="button" href="{{ route('teacher_data.index') }}" count="456" title="Data Guru"/>
                </div>
            </div>
        </div>

</x-app-layout>
