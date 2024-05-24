<!-- ========== HEADER ========== -->
<header class="flex flex-wrap sm:justify-start sm:flex-nowrap z-50 w-full bg-white border-b border-gray-200 text-sm py-3 sm:py-0 dark:bg-neutral-800 dark:border-neutral-700">
    <nav x-data="{ sidebarOpen: false }" class="relative max-w-[85rem] flex flex-wrap basis-full items-center w-full mx-auto px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8" aria-label="Global">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center">
                <!-- Sidebar Toggle -->
                <div class="sticky top-0 inset-x-0 z-20 bg-white border-y dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex items-center py-4">
                        <button @click="open = !open" type="button" class="text-gray-500 hover:text-gray-600" aria-label="Toggle navigation">
                            <span class="sr-only">Toggle Navigation</span>
                            <svg class="size-5" width="16" height="16" fill="currentColor" viewBox="0 0 15 15">
                                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- End Sidebar Toggle -->

                <!-- Title -->
                <a class="h-9 w-auto fill-current text-gray-800 dark:text-gray-200 font-bold flex flex-col gap-y-4 gap-x-0 mt-5 sm:flex-row sm:items-center sm:justify-end sm:gap-y-0 sm:gap-x-7 sm:mt-0 sm:ps-7 ms-4" href="{{ route('dashboard') }}">
                    E-RAPOR SDN DAWUAN
                </a>
            </div>

            <!-- Log Out -->
            <div class="flex flex-col gap-y-4 gap-x-0 mt-5 sm:flex-row sm:items-center sm:justify-end sm:gap-y-0 sm:gap-x-7 sm:mt-0 sm:ps-7 ms-4">
                <!-- Authentication -->
                <form class="px-6 sm:py-4 sm:px-0" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-nav-link>
                </form>
            </div>
        </div>
    </nav>
</header>
<!-- ========== END HEADER ========== -->

<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sidebar', () => ({
            open: false,
            toggleSidebar() {
                this.open = !this.open;
            }
        }));
    });
</script>

