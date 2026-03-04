<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Content Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form
                    method="POST"
                    action="{{ route('content-items.store') }}"
                    x-data="{
                        type: '{{ old('type', 'card') }}',
                        published: {{ old('published', '1') }},
                    }"
                    class="space-y-6"
                >
                    @csrf

                    {{-- Type --}}
                    <div>
                        <x-input-label for="type" :value="__('Type')" />
                        <select
                            id="type"
                            name="type"
                            x-model="type"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                            required
                        >
                            <option value="card">card</option>
                            <option value="poster">poster</option>
                            <option value="richtext">richtext</option>
                            <option value="group">group</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('type')" />
                    </div>

                    {{-- Title (card / poster / group) --}}
                    <div x-show="type !== 'richtext'">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    {{-- Description (card) --}}
                    <div x-show="type === 'card'">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    {{-- Markdown content (richtext) --}}
                    <div x-show="type === 'richtext'">
                        <x-input-label for="content" :value="__('Markdown Content')" />
                        <textarea id="content" name="content" rows="10" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-mono">{{ old('content') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>

                    {{-- Thumbnail URL (card) --}}
                    <div x-show="type === 'card'">
                        <x-input-label for="thumbnail_url" :value="__('Thumbnail URL')" />
                        <x-text-input id="thumbnail_url" name="thumbnail_url" type="url" class="mt-1 block w-full" :value="old('thumbnail_url')" placeholder="https://..." />
                        <x-input-error class="mt-2" :messages="$errors->get('thumbnail_url')" />
                    </div>

                    {{-- Image URL (poster) --}}
                    <div x-show="type === 'poster'">
                        <x-input-label for="image_url" :value="__('Image URL')" />
                        <x-text-input id="image_url" name="image_url" type="url" class="mt-1 block w-full" :value="old('image_url')" placeholder="https://..." />
                        <x-input-error class="mt-2" :messages="$errors->get('image_url')" />
                    </div>

                    {{-- Date & Time (card) --}}
                    <div x-show="type === 'card'" class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date')" />
                            <x-input-error class="mt-2" :messages="$errors->get('date')" />
                        </div>
                        <div>
                            <x-input-label for="time" :value="__('Time')" />
                            <x-text-input id="time" name="time" type="text" class="mt-1 block w-full" :value="old('time')" placeholder="18:00" />
                            <x-input-error class="mt-2" :messages="$errors->get('time')" />
                        </div>
                    </div>

                    {{-- Link URL (card / poster) --}}
                    <div x-show="type === 'card' || type === 'poster'">
                        <x-input-label for="link_url" :value="__('Link URL')" />
                        <x-text-input id="link_url" name="link_url" type="url" class="mt-1 block w-full" :value="old('link_url')" placeholder="https://..." />
                        <x-input-error class="mt-2" :messages="$errors->get('link_url')" />
                    </div>

                    {{-- CTA Text (card) --}}
                    <div x-show="type === 'card'">
                        <x-input-label for="link_text" :value="__('CTA Text')" />
                        <x-text-input id="link_text" name="link_text" type="text" class="mt-1 block w-full" :value="old('link_text')" placeholder="{{ __('Află mai multe') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('link_text')" />
                    </div>

                    {{-- Parent group (non-group types) --}}
                    <div x-show="type !== 'group'">
                        <x-input-label for="parent_id" :value="__('Parent Group (optional)')" />
                        <select id="parent_id" name="parent_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">— {{ __('None') }} —</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" {{ (old('parent_id', $parentId) == $group->id) ? 'selected' : '' }}>
                                    {{ $group->title }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
                    </div>

                    {{-- Locations --}}
                    <div>
                        <x-input-label :value="__('Visible at locations (leave empty = all)')" />
                        <div class="mt-2 space-y-2">
                            @foreach ($locations as $location)
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input
                                        type="checkbox"
                                        name="locations[]"
                                        value="{{ $location->id }}"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        {{ in_array($location->id, old('locations', [])) ? 'checked' : '' }}
                                    >
                                    {{ $location->title }}
                                    <span class="text-xs text-gray-400 font-mono">{{ $location->slug }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('locations')" />
                    </div>

                    {{-- Published --}}
                    <div class="flex items-center gap-3">
                        <input
                            id="published"
                            name="published"
                            type="checkbox"
                            value="1"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            {{ old('published', '1') ? 'checked' : '' }}
                        >
                        <label for="published" class="text-sm text-gray-700">{{ __('Published (visible in public API)') }}</label>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button>{{ __('Create') }}</x-primary-button>
                        <a href="{{ route('content-items.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
