<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <p class="font-semibold text-gray-800 leading-tight text-2xl">
                {{ __('Edit Penilaian Keterampilan') }}
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form action="{{ route('grade.updateSkillScore', ['studentId' => $studentId, 'classSubjectId' => $classSubjectId, 'semesterYearId' => $defaultSemesterYearId, 'assessmentType' => $assessmentType]) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label for="assessment_type" class="block text-sm font-medium text-gray-700">Tipe Penilaian</label>
                                <input type="text" name="assessment_type" id="assessment_type" value="{{ $assessmentType }}" class="mt-1 p-2 border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-500 block w-full" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="score" class="block text-sm font-medium text-gray-700">Nilai</label>
                                <input type="text" name="score" id="score" value="{{ $knowledgeScore->score ?? '' }}" class="mt-1 p-2 border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-500 block w-full">
                            </div>
                            <div class="mb-4">
                                <label for="final_score" class="block text-sm font-medium text-gray-700">Nilai Akhir</label>
                                <input type="text" name="final_score" id="final_score" value="{{ $knowledgeScore->final_score ?? '' }}" class="mt-1 p-2 border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-500 block w-full" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="grade" class="block text-sm font-medium text-gray-700">Grade</label>
                                <input type="text" name="grade" id="grade" value="{{ $knowledgeScore->grade ?? '' }}" class="mt-1 p-2 border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-500 block w-full" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 p-2 border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-500 block w-full">{{ $knowledgeScore->description ?? '' }}</textarea>
                            </div>
                            <div class="flex justify-end space-x-4 mt-4 w-full">
                                <a href="{{ route('grade.detailSkillScore', ['studentId' => $studentId, 'classSubjectId' => $classSubjectId, 'semesterYearId' => $defaultSemesterYearId]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-light-gray border border-gray-300 rounded-md font-semibold text-xs text-slate-600 tracking-widest
                                    shadow-sm hover:bg-slate-400 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    Batal
                                </a>

                                <x-primary-button>Perbaharui</x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
