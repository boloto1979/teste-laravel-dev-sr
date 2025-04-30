@props(['title', 'value', 'color' => 'indigo'])

<div class="overflow-hidden bg-white rounded-lg shadow">
    <div class="p-4 sm:p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0 p-2 sm:p-3 bg-{{ $color }}-500 rounded-md">
                {{ $slot }}
            </div>
            <div class="flex-1 min-w-0 ml-4">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        {{ $title }}
                    </dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">
                        {{ $value }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>