<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Akun Guru') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-lg shadow-md mx-4 overflow-hidden min-w-[300px] min-h-[500px]">
        <x-table header="Daftar Akun Guru" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
            <x-slot name="header">
                <tr>
                    <th>No</th>
                    <th>Nama Akun</th>
                    <th>Email</th>
                    <th>NUPTK</th>
                    <th>NIP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </x-slot>
            @php $num = 1; @endphp
            @foreach($teacherAccounts as $account)
                <tr class="text-center">
                    <td>{{ $num++ }}</td>
                    <td>{{ ucwords(strtolower($account->user->name))}}</td>
                    <td>{{ ucwords(strtolower( $account->user->email))}}</td>
                    <td>{{ $account->user->nuptk }}</td>
                    <td>{{ $account->user->nip }}</td>

                    <td class="size-px whitespace-nowrap">
                        <div class="px-6 py-3">
                            @if( $account->user->status == 'active')
                                <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full dark:bg-green-500/10 dark:text-green-500">
                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </td>
                    <td :class="{ 'flex flex-row justify-center items-center gap-2': sidebarOpen, 'justify-center items-center gap-2': !sidebarOpen }">
                        <x-edit-primary-button tag="a" href="{{ route('account.teacher.edit', $account->id) }}" class="flex items-center justify-center min-w-[60px]">
                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                        </x-edit-primary-button>
                    </td>
                </tr>
            @endforeach
        </x-table>
    </div>
</x-app-layout>
