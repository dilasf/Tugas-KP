@props([
    'header' => '',
])

<div class="flex flex-col overflow-hidden"> <!-- Added overflow-hidden -->
    <div class="-mx-6">
        <div class="inline-block min-w-full py-1 md:px-2 lg:px-10">
            <div class="overflow-hidden ring-1 ring-gray-700 ring-opacity-5">
                <table class="min-w-full divide-y divide-gray-300 table-fixed"> <!-- Use table-fixed for fixed width -->
                    <thead class="bg-gray-800">
                        <tr>
                            {!! $header !!}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('th').forEach(el => el.classList.add("px-2", "py-2", "text-center", "text-xs", "font-medium", "text-white", "uppercase", "tracking-wider", 'border', 'border-gray-700'));
    document.querySelectorAll('td').forEach(el => el.classList.add("px-2", "py-2", "whitespace-nowrap", "text-sm", "font-medium", "text-gray-900", 'border', 'border-gray-300'));
</script>
