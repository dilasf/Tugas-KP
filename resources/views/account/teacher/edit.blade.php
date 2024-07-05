<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('account.teacher.index') }}">
                <img src="{{ asset('img/back_logo.png') }}" class="w-[30px] h-[30px] mr-2 cursor-pointer">
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Formulir Edit Data Guru') }}
            </h2>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-10">
        <div class="top-0 left-0 w-full h-10 rounded-t-md bg-light-blue flex items-center justify-center text-white font-semibold text-md leading-tight">
            {{ __('Formulir Edit Data Guru') }}
        </div>
        <div class="bg-white overflow-hidden shadow-sm">
            <div class="p-6 text-black">
                <div class="max-h-[70vh] overflow-y-auto">
                    <form method="post" action="{{ route('account.teacher.update', $teacherAccount->id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Form fields -->
                        <div class="max-w-3xl">
                            <x-input-label for="name" value="Nama Pengguna"/>
                            <x-text-input id="name" type="text" name="name" class="mt-1 block w-full bg-zinc-100" value="{{ old('name', $teacherAccount->user->name) }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="email" value="Email"/>
                            <x-text-input id="email" type="email" name="email" class="mt-1 block w-full bg-zinc-100" value="{{ old('email', $teacherAccount->user->email) }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="max-w-3xl">
                            <x-input-label for="password" value="Password"/>
                            <x-text-input id="password" type="password" name="password" class="mt-1 block w-full bg-zinc-100"/>
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <!-- Continue with other fields as per your requirement -->

                        <div class="flex justify-end space-x-4 mt-4 w-full">
                            <x-secondary-button tag="a" href="{{ route('account.teacher.index') }}">Batal</x-secondary-button>
                            <x-primary-button>Perbaharui</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
