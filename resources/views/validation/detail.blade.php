<!-- resources/views/rapors/validation/detail.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Rapor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-bold mb-4">{{ $rapor->grade->student->student_name }}</h3>
                    <p><strong>Kelas:</strong> {{ $rapor->grade->classSubject->class->class_name }}</p>
                    <p><strong>Semester:</strong> {{ $rapor->grade->semesterYear->semester }}</p>
                    <p><strong>Status:</strong> {{ $rapor->status }}</p>
                    <p><strong>Social Attitudes:</strong> {{ $rapor->social_attitudes }}</p>
                    <p><strong>Spiritual Attitude:</strong> {{ $rapor->spiritual_attitude }}</p>
                    <p><strong>Suggestion:</strong> {{ $rapor->suggestion }}</p>

                    <form action="{{ route('rapors.validation.approve', ['id' => $rapor->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-4">Validasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
