@props([
    'header' => '',
])

<div class="flex flex-col">
    <div class="-mx-6">
        <div class="inline-block min-w-full py-1 md:px-2 lg:px-10">
            <div class="overflow-hidden ring-1 ring-black ring-opacity-5">
                <table class="min-w-full divide-y divide-white">
                    <thead class="bg-custom-dark">
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
    document.querySelectorAll('th').forEach(el => el.classList.add("px-4", "py-3", "text-center", "text-xs", "font-medium", "text-white", "uppercase", "tracking-wider", 'border'));
    document.querySelectorAll('td').forEach(el => el.classList.add("px-4", "py-2", "whitespace-nowrap", "text-sm", "font-medium", "text-gray-900", 'border'));

</script>
