
  <!-- Sidebar -->
  <div class="overflow-x-auto">
    <div class="px-6">
        <a class="flex-none text-xl font-semibold text-white focus:outline-none focus:ring-1 focus:ring-gray-600" href="#" aria-label="Admin">Admin</a>
    </div>

    <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
        <ul class="space-y-1.5">
            <li class="col-span-3 bg-side-dark">
                <x-nav-link :href="route('dashboard-admin')" :active="request()->routeIs('dashboard')" class=" font-semibold px-4 py-2">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <div class = "px-1">
                            Dashboard
                        </div>
                </x-nav-link>
            </li>

            <div class="px-8 py-2">
                <a class="flex-none text-sm font-semibold text-white focus:outline-none focus:ring-1 focus:ring-gray-600 py-2" href="#" aria-label="MASTER DATA">MASTER DATA</a>
            </div>

            <li class="hs-accordion" id="users-accordion">
                <button type="button" class="hs-accordion-toggle w-full text-start flex items-center gap-x-4 py-2 px-2.5 hs-accordion-active:text-white hs-accordion-active:hover:bg-side-dark text-sm text-slate-200 hover:bg-hover-side hover:text-white focus:outline-none focus:bg-side-dark">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Manajemen Pengguna

                    <svg class="hs-accordion-active:block ms-auto hidden size-4" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    <svg class="hs-accordion-active:hidden ms-auto block size-4" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div id="users-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                    <ul class="hs-accordion-group ps-3 pt-2" data-hs-accordion-always-open>
                        @role('admin')
                            <li>
                                <x-dropdown-link :href="route('teacher_data.index')" :active="request()->routeIs('teacher')">
                                {{ __('Data Guru') }}
                                </x-dropdown-link>
                            </li>
                        @endrole
                            <li>
                                <x-dropdown-link :href="route('student_data.index')" :active="request()->routeIs('student')">
                                    {{ __('Data Siswa') }}
                                </x-dropdown-link>
                            </li>
                            @role('admin')
                            <li class="hs-accordion" id="users-accordion-sub-2">
                                <button type="button" class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 hs-accordion-active:text-white hs-accordion-active:hover:bg-side-dark text-sm text-slate-200 hover:bg-hover-side hover:text-white focus:outline-none focus:bg-side-dark">
                                    Akun
                                    <svg class="hs-accordion-active:block ms-auto hidden size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                                    <svg class="hs-accordion-active:hidden ms-auto block size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div id="users-accordion-sub-2-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden ps-2">
                                    <ul class="pt-2 ps-2">
                                        <li>
                                            <x-dropdown-link :href="route('account.student.index')" :active="request()->routeIs('accountstudent')">
                                                {{ __('Siswa') }}
                                            </x-dropdown-link>
                                        </li>
                                        <li>
                                            <x-dropdown-link :href="route('account.teacher.index')" :active="request()->routeIs('accountteacher')">
                                                {{ __('Guru') }}
                                            </x-dropdown-link>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @endrole
                    </ul>
                </div>
            </li>

            @role('admin')
            <li class="hs-accordion" id="account-accordion">
                <button type="button" class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 hs-accordion-active:text-white hs-accordion-active:hover:bg-side-dark text-sm text-slate-200 hover:bg-hover-side hover:text-white focus:outline-none focus:bg-side-dark">

                    <img src="{{ asset('img/pembelajaran_logo.png') }}" class="w-[20px] h-[20px]">
                        Kelas dan Pengajaran

                    <svg class="hs-accordion-active:block ms-auto hidden size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    <svg class="hs-accordion-active:hidden ms-auto block size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div id="account-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                    <ul class="pt-2 ps-2">
                        <li>
                            <x-dropdown-link :href="route('subject.semester_year.index')" :active="request()->routeIs('subject.semester_year')">
                            {{ __('Semester & Tahun Ajaran') }}
                            </x-dropdown-link>
                        </li>
                        <li>
                            <x-dropdown-link :href="route('subject.index')" :active="request()->routeIs('subject')">
                                {{ __('Mata Pelajaran') }}
                            </x-dropdown-link>
                        </li>
                        <li>
                            <x-dropdown-link :href="route('class.index')" :active="request()->routeIs('class')">
                            {{ __('Kelas') }}
                            </x-dropdown-link>
                        </li>
                    </ul>
                </div>
            @endrole

            <div class="px-8 py-2">
                <a class="flex-none text-sm font-semibold text-white focus:outline-none focus:ring-1 focus:ring-gray-600 py-3" href="#" aria-label="SAYA">SAYA</a>
            </div>
            <li class="col-span-3">
                <x-nav-link :href="route('profile.edit')" class=" bg-side-dark font-semibold px-3 py-2">
                    <img src="{{ asset('img/saya_logo.png') }}" class="w-[24px] h-[24px]">
                        <div class = "gap-x-1">
                            {{ __('Profile') }}
                        </div>
                </x-nav-link>
            </li>

            @role('guru_mapel|guru_kelas')
            <li class="col-span-3">
                <x-nav-link :href="route('class-subjects.index')" class=" bg-side-dark font-semibold px-3 py-2">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                        <div class = "gap-x-1">
                            {{ __('Penilaian') }}
                        </div>
                </x-nav-link>
            </li>
            @endrole
      </ul>
    </nav>
  </div>
  <!-- End Sidebar -->
