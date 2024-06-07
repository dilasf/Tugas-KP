<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

    <div class="flex-grow bg-white rounded-lg shadow-md mx-6 my-1 p-10 min-h-[calc(70vh-5px)]">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 my-4 gap-10">

                <div style="width: 259px;">
                    <x-box-purple tag="button" href="{{ route('teacher_data.index') }}" :count="$teacherCount" title="Data Guru"/>
                </div>


                <div style="width: 259px;">
                    <x-box-aqua tag="button" href="{{ route('student_data.index') }}" :count="$studentCount" title="Data Siswa"/>
                </div>
{{--
                <div style="width: 259px;">
                    <x-box-blue tag="button" href="{{ route('account.index') }}" :count="0" title="Akun"/>
                </div> --}}

                <div style="width: 259px;">
                    <x-box-green tag="button" href="{{ route('subject.index') }}" :count="$subjectCount" title="Mata Pelajaran"/>
                </div>

                <div style="width: 259px;">
                    <x-box-orange tag="button" href="{{ route('class.index') }}" :count="$classCount" title="Kelas"/>
                </div>

                <div style="width: 259px;" >
                    <x-box-purple tag="button" href="{{ route('subject.semester_year.index') }}" :count="$semesterYearCount" title="Data Semester & TA"/>
                </div>
            </div>
        </div>

</x-app-layout>
