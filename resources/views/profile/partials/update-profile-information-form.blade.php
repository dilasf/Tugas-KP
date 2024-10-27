<section>
    <div class="dark:bg-gray-800 p-10">
        <header class="mb-10">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Informasi Profil dan Update Password') }}
            </h2>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 w-full">
            @csrf
            @method('patch')

            <!-- Foto Profil -->
            <div class="flex justify-center mb-10 max-w-5xl">
                    @if ($user->teacher_id && $user->teacher->photo)
                        <img src="{{ asset('storage/' . $user->teacher->photo) }}" alt="Foto Guru" class="w-[100px] h-[150px]">
                    @elseif ($user->student_id && $user->student->student_photo)
                        <img src="{{ asset('storage/' . $user->student->student_photo) }}" alt="Foto Siswa" class="w-[100px] h-[150px]">
                    @else
                        <img src="{{ asset('img/profil.png') }}" alt="No photo" class="w-[120px] h-[120px]">
                    @endif
            </div>

            <!-- Informasi Profil -->
            <div class="space-y-4 max-w-5xl">
                <div class="flex items-center">
                    <x-input-label for="name" :value="__('Nama')" class="mr-4 w-1/4" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-3/4" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div class="flex items-center">
                    <x-input-label for="email" :value="__('Email')" class="mr-4 w-1/4" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-3/4" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="flex items-center">
                    <x-input-label for="current_password" :value="__('Kata Sandi Sebelumnya')" class="mr-4 w-1/4" />
                    <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-3/4" autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <x-input-label for="password" :value="__('Kata Sandi Baru')" class="mr-4 w-1/4" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="mr-4 w-1/4" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-3/4" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center gap-4 mt-6">
                <x-primary-button>{{ __('Simpan') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Tersimpan.') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>
