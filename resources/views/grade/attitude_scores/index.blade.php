<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Nilai Sikap') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-lg shadow-md mx-4 overflow-hidden min-w-[300px] min-h-[500px] border border-gray-200">
        <div class="flex justify-end px-6 py-4">
            <x-primary-button tag="a" href="{{ route('grade.attitude_scores.create') }}">
                <span class="text-12px ml-1">{{ __('+ Tambah Assessment') }}</span>
            </x-primary-button>
        </div>
        <div class="text-black max-h-[calc(100vh-200px)] overflow-y-auto">
            <x-table header="Header Content" :sidebarOpen="$sidebarOpen" class="overflow-x-auto mx-auto">
                <x-slot name="header">
                    <tr>
                        <th>No</th>
                        <th>Jenis Penilaian</th>
                        <th>Aksi</th>
                    </tr>
                </x-slot>
                @php $num=1; @endphp
                @foreach($assessmentTypes as $assessmentType)
                <tr>
                    <td class="text-center">{{ $num++ }}</td>
                    <td>{{ $assessmentType->assessment_type }}</td>
                    <td class="text-center">
                        <x-edit-primary-button tag="a" :href="route('grade.attitude_scores.edit', ['assessment_type' => $assessmentType->assessment_type])"
                            class="flex items-center justify-center min-w-[60px]">
                            <img src="{{ asset('img/edit-brush_logo.png') }}" class="w-[13px] h-[13px]">
                            <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Edit') }}</span>
                        </x-edit-primary-button>


                        <x-danger-button
                        x-data=""
                        x-on:click.prevent="
                            $dispatch('open-modal', 'confirm-assessment-type-deletion');
                            $dispatch('set-action', '{{ route('grade.attitude_scores.destroy', ['assessment_type' => $assessmentType->assessment_type]) }}');"
                        class="flex items-center justify-center min-w-[60px]">
                        <img src="{{ asset('img/garbage_logo.png') }}" class="w-[13px] h-[13px]">
                        <span x-show="!sidebarOpen" class="ml-1 text-[10px]">{{ __('Hapus') }}</span>
                    </x-danger-button>
                    </td>
                </tr>
                @endforeach
            </x-table>

            <x-modal name="confirm-assessment-type-deletion" focusable maxWidth="xl">
                <form method="post" x-bind:action="action" class="p-6">
                    @csrf
                    @method('delete')
                    <div class="flex items-start">
                        <!-- Icon -->
                        <span class="flex-shrink-0 inline-flex justify-center items-center size-[46px] sm:w-[62px] sm:h-[62px] rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100 mr-6">
                            <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </span>
                        <!-- End Icon -->

                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Apakah anda yakin akan menghapus data?') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Setelah proses dilaksanakan. Data akan dihilangkan secara permanen.') }}
                            </p>
                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Batal') }}
                                </x-secondary-button>
                                <x-danger-button class="ml-3">
                                    {{ __('Hapus!!!') }}
                                </x-danger-button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</x-app-layout>
