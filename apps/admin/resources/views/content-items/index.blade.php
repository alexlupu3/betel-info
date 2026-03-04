<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Content') }}
            </h2>
            <a href="{{ route('content-items.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add Item') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status') === 'item-created')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ __('Item created.') }}</div>
            @elseif (session('status') === 'item-updated')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ __('Item updated.') }}</div>
            @elseif (session('status') === 'item-deleted')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ __('Item deleted.') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                @if ($items->isEmpty())
                    <div class="p-6 text-center text-gray-500 text-sm">
                        {{ __('No content items yet.') }}
                        <a href="{{ route('content-items.create') }}" class="text-indigo-600 hover:underline">{{ __('Add one now.') }}</a>
                    </div>
                @else
                    {{-- Type badge colours --}}
                    @php
                        $typeBadge = [
                            'card'     => 'bg-blue-100 text-blue-700',
                            'poster'   => 'bg-purple-100 text-purple-700',
                            'richtext' => 'bg-yellow-100 text-yellow-700',
                            'group'    => 'bg-gray-100 text-gray-700',
                        ];
                    @endphp

                    <div
                        x-data="{
                            reorder(items) {
                                fetch('{{ route('content-items.reorder') }}', {
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    },
                                    body: JSON.stringify({ items }),
                                });
                            }
                        }"
                    >
                        {{-- Top-level sortable list --}}
                        <ul
                            id="sortable-root"
                            class="divide-y divide-gray-100"
                        >
                            @foreach ($items as $item)
                                <li
                                    data-id="{{ $item->id }}"
                                    data-parent="{{ $item->parent_id }}"
                                    class="group/row"
                                >
                                    <div class="flex items-center gap-3 px-4 py-4 hover:bg-gray-50">
                                        {{-- Drag handle --}}
                                        <span class="cursor-grab text-gray-300 hover:text-gray-500 select-none drag-handle" title="{{ __('Drag to reorder') }}">⠿</span>

                                        {{-- Type badge --}}
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $typeBadge[$item->type] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $item->type }}
                                        </span>

                                        {{-- Title / content preview --}}
                                        <span class="flex-1 text-sm text-gray-800 truncate">
                                            @if ($item->type === 'richtext')
                                                <span class="text-gray-400 italic">{{ Str::limit($item->content, 60) }}</span>
                                            @else
                                                {{ $item->title }}
                                            @endif
                                        </span>

                                        {{-- Location chips --}}
                                        @if ($item->locations->isNotEmpty())
                                            <div class="hidden sm:flex gap-1">
                                                @foreach ($item->locations as $loc)
                                                    <span class="px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-600 text-xs font-mono">{{ $loc->slug }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Published indicator --}}
                                        @if ($item->published)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">{{ __('Live') }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">{{ __('Draft') }}</span>
                                        @endif

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-3 text-sm font-medium">
                                            @if ($item->type === 'group')
                                                <a href="{{ route('content-items.create', ['parent_id' => $item->id]) }}" class="text-gray-500 hover:text-gray-700">{{ __('+ Child') }}</a>
                                            @endif
                                            <a href="{{ route('content-items.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                            <form method="POST" action="{{ route('content-items.destroy', $item) }}" class="inline" onsubmit="return confirm('{{ __('Delete this item?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Children (groups) --}}
                                    @if ($item->children->isNotEmpty())
                                        <ul
                                            id="sortable-group-{{ $item->id }}"
                                            class="border-t border-gray-100 bg-gray-50 divide-y divide-gray-100"
                                            data-parent-id="{{ $item->id }}"
                                        >
                                            @foreach ($item->children as $child)
                                                <li data-id="{{ $child->id }}" data-parent="{{ $child->parent_id }}">
                                                    <div class="flex items-center gap-3 pl-8 pr-4 py-4 hover:bg-gray-100">
                                                        <span class="cursor-grab text-gray-300 hover:text-gray-500 select-none drag-handle">⠿</span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $typeBadge[$child->type] ?? 'bg-gray-100 text-gray-600' }}">
                                                            {{ $child->type }}
                                                        </span>
                                                        <span class="flex-1 text-sm text-gray-700 truncate">{{ $child->title }}</span>

                                                        @if ($child->locations->isNotEmpty())
                                                            <div class="hidden sm:flex gap-1">
                                                                @foreach ($child->locations as $loc)
                                                                    <span class="px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-600 text-xs font-mono">{{ $loc->slug }}</span>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        @if ($child->published)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">{{ __('Live') }}</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">{{ __('Draft') }}</span>
                                                        @endif

                                                        <div class="flex items-center gap-3 text-sm font-medium">
                                                            <a href="{{ route('content-items.edit', $child) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                                            <form method="POST" action="{{ route('content-items.destroy', $child) }}" class="inline" onsubmit="return confirm('{{ __('Delete this item?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SortableJS for drag-and-drop reordering --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
    <script>
        function initSortable(el, parentId) {
            Sortable.create(el, {
                handle: '.drag-handle',
                animation: 150,
                onEnd() {
                    const items = [...el.querySelectorAll(':scope > li')].map((li, idx) => ({
                        id: parseInt(li.dataset.id),
                        sort_order: idx,
                        parent_id: parentId ?? null,
                    }));
                    fetch('{{ route('content-items.reorder') }}', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify({ items }),
                    });
                },
            });
        }

        initSortable(document.getElementById('sortable-root'), null);

        document.querySelectorAll('[id^="sortable-group-"]').forEach(el => {
            initSortable(el, parseInt(el.dataset.parentId));
        });
    </script>
</x-app-layout>
