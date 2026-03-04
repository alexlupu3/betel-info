<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Location') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">{{ $location->title }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Update this location\'s branding and color scheme.') }}</p>
                    </header>

                    <form method="POST" action="{{ route('locations.update', $location) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Basic info --}}
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $location->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="slug" :value="__('Slug')" />
                            <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full font-mono" :value="old('slug', $location->slug)" required />
                            <p class="mt-1 text-xs text-gray-500">{{ __('Lowercase letters, numbers, and hyphens only.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>{{ old('description', $location->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="logo_path" :value="__('Logo Path')" />
                            <div class="mt-1 flex items-center gap-3">
                                @if ($location->logo_path)
                                    <img src="{{ $location->logo_path }}" alt="" class="h-10 w-10 rounded object-cover border border-gray-200 shrink-0">
                                @endif
                                <x-text-input id="logo_path" name="logo_path" type="text" class="block w-full" :value="old('logo_path', $location->logo_path)" placeholder="/locations/betel-centru/logo.jpg" />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ __('Optional. Path or URL to the location logo.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('logo_path')" />
                        </div>

                        {{-- Theme colors --}}
                        <div class="border-t border-gray-100 pt-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('Theme Colors') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @php
                                    $colorFields = [
                                        ['name' => 'primary_color',       'label' => 'Primary',       'required' => true],
                                        ['name' => 'primary_light_color', 'label' => 'Primary Light', 'required' => false],
                                        ['name' => 'primary_dark_color',  'label' => 'Primary Dark',  'required' => false],
                                        ['name' => 'accent_color',        'label' => 'Accent',        'required' => false],
                                        ['name' => 'accent_light_color',  'label' => 'Accent Light',  'required' => false],
                                        ['name' => 'accent_dark_color',   'label' => 'Accent Dark',   'required' => false],
                                    ];
                                @endphp

                                @foreach ($colorFields as $field)
                                    @php $value = old($field['name'], $location->{$field['name']}) ?? '#000000' @endphp
                                    <div x-data="{ hex: '{{ $value }}' }">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            {{ $field['label'] }}
                                            @if (! $field['required'])<span class="text-gray-400 font-normal">({{ __('optional') }})</span>@endif
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <input
                                                type="color"
                                                x-model="hex"
                                                @input="hex = $event.target.value"
                                                class="h-9 w-12 rounded border border-gray-300 cursor-pointer p-0.5"
                                            >
                                            <input
                                                type="text"
                                                name="{{ $field['name'] }}"
                                                x-model="hex"
                                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-mono uppercase"
                                                placeholder="#000000"
                                                maxlength="7"
                                                @if ($field['required']) required @endif
                                            >
                                        </div>
                                        <x-input-error class="mt-1" :messages="$errors->get($field['name'])" />
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Default flag --}}
                        <div class="flex items-center gap-3">
                            <input id="is_default" name="is_default" type="checkbox" value="1"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                {{ old('is_default', $location->is_default) ? 'checked' : '' }}>
                            <label for="is_default" class="text-sm text-gray-700">{{ __('Set as default location') }}</label>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            <a href="{{ route('locations.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>

                            @if (session('status') === 'location-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600"
                                >{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">{{ __('Delete Location') }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Permanently delete this location. This cannot be undone.') }}</p>
                    </header>

                    <form method="POST" action="{{ route('locations.destroy', $location) }}" class="mt-6" onsubmit="return confirm('{{ __('Delete this location? This cannot be undone.') }}')">
                        @csrf
                        @method('DELETE')

                        <x-danger-button>{{ __('Delete Location') }}</x-danger-button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
