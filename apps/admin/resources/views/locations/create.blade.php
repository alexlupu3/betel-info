<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Location') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">{{ __('Location Details') }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Add a new church location with its branding.') }}</p>
                    </header>

                    <form method="POST" action="{{ route('locations.store') }}" class="mt-6 space-y-6">
                        @csrf

                        {{-- Basic info --}}
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="slug" :value="__('Slug')" />
                            <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full font-mono" :value="old('slug')" required placeholder="e.g. betel-centru" />
                            <p class="mt-1 text-xs text-gray-500">{{ __('Lowercase letters, numbers, and hyphens only.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="logo_path" :value="__('Logo Path')" />
                            <x-text-input id="logo_path" name="logo_path" type="text" class="mt-1 block w-full" :value="old('logo_path')" placeholder="/locations/betel-centru/logo.jpg" />
                            <p class="mt-1 text-xs text-gray-500">{{ __('Optional. Path or URL to the location logo.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('logo_path')" />
                        </div>

                        {{-- Theme colors --}}
                        <div class="border-t border-gray-100 pt-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('Theme Colors') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @php
                                    $colorFields = [
                                        ['name' => 'primary_color',       'label' => 'Primary',       'required' => true,  'default' => '#000000'],
                                        ['name' => 'primary_light_color', 'label' => 'Primary Light', 'required' => false, 'default' => '#f2f2f2'],
                                        ['name' => 'primary_dark_color',  'label' => 'Primary Dark',  'required' => false, 'default' => '#000000'],
                                        ['name' => 'accent_color',        'label' => 'Accent',        'required' => false, 'default' => '#000000'],
                                        ['name' => 'accent_light_color',  'label' => 'Accent Light',  'required' => false, 'default' => '#f2f2f2'],
                                        ['name' => 'accent_dark_color',   'label' => 'Accent Dark',   'required' => false, 'default' => '#000000'],
                                    ];
                                @endphp

                                @foreach ($colorFields as $field)
                                    <div x-data="{ hex: '{{ old($field['name'], $field['default']) }}' }">
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
                                {{ old('is_default') ? 'checked' : '' }}>
                            <label for="is_default" class="text-sm text-gray-700">{{ __('Set as default location') }}</label>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <x-primary-button>{{ __('Create') }}</x-primary-button>
                            <a href="{{ route('locations.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
